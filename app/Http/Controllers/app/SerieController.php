<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\TraitHelpers;
use App\Models\Entidade;
use Illuminate\Support\Str;
use App\Models\Serie;
use App\Models\User;
use App\Traits\UsesAgtConfig;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use PDF;

class SerieController extends Controller
{

    use TraitHelpers, UsesAgtConfig;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->loadAgtConfig();
    }

    public function home()
    {
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.serie.index', $head);
    }

    public function show($id) {}

    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $perPage = $request->perPage ?? 5;

        $query = Serie::query();

        $query->where('entidade_id', $user->entidade_id);

        if ($request->seriesCode)
            $query->where('seriesCode', 'like', '%' . $request->seriesCode . '%');

        if ($request->documentType)
            $query->where('documentType', $request->documentType);

        if ($request->seriesYear)
            $query->where('seriesYear', $request->seriesYear);

        return response()->json(
            $query->orderBy('id', 'desc')->paginate($perPage)
        );
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        if (!$user->can('criar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $taxRegistrationNumber = $entidade->empresa->nif ?? NULL;
        $privateCustomerKey = $entidade->empresa->private_key ?? NULL;

        $request->validate([
            'seriesYear' => 'required|digits:4',
            'documentType' => 'required|string|size:2', // FR, FT, RG, OT, EC, PP
            'establishmentNumber' => 'required|string|max:20',
            'seriesContingencyIndicator' => 'required|in:S,N',
        ], [
            'seriesYear.required' => 'campo obrigatório',
            'documentType.required' => 'campo obrigatório',
            'establishmentNumber.required' => 'campo obrigatório',
            'seriesContingencyIndicator.required' => 'campo obrigatório',
            'seriesYear.digits' => 'O ano da série deve conter 4 dígitos.',
            'documentType.size' => 'O tipo de documento deve ter exatamente 2 caracteres.',
            'seriesContingencyIndicator.in' => 'O indicador de contingência deve ser S ou N.',
        ]);

        $isSerie = Serie::where('seriesYear', $request->seriesYear)
            ->where('documentType', $request->documentType)
            ->where('establishmentNumber', $request->establishmentNumber)
            ->where('taxRegistrationNumber', $taxRegistrationNumber)
            // ->where('taxRegistrationNumber', $this->taxRegistrationNumber)
            ->first();

        if ($isSerie) {
            return response()->json([
                'success' => false,
                'message' => 'A série já foi cadastrada.'
            ], 404);
        }

        $nowUtc = Carbon::now('UTC');

        $jwsSignature = [
            "taxRegistrationNumber" => $taxRegistrationNumber,
            // "taxRegistrationNumber" => $this->taxRegistrationNumber,
            "seriesYear" => $request->seriesYear,
            "documentType" => $request->documentType,
            "establishmentNumber" => $request->establishmentNumber,
            "seriesContingencyIndicator" => $request->seriesContingencyIndicator
        ];
        
        $jwsSignature = JWT::encode($jwsSignature, $privateCustomerKey, 'RS256');

        // $jwsSignature = JWT::encode($jwsSignature, $this->privateCustomerKey, 'RS256');

        $submissionUUID = Str::uuid()->toString();

        $invoice = [
            "schemaVersion" => $this->schemaVersion(),
            "submissionUUID" => $submissionUUID,
            "taxRegistrationNumber" => $this->taxRegistrationNumber,
            "submissionTimeStamp" => $nowUtc->format('Y-m-d\TH:i:s\Z'),
            "softwareInfo" => [
                "softwareInfoDetail" => $this->softwareInfoDetail(),
                "jwsSoftwareSignature" => $this->jwsSoftwareSignature()
            ],
            "seriesYear" =>  $request->seriesYear,
            "documentType" => $request->documentType,
            "establishmentNumber" => $request->establishmentNumber,
            "jwsSignature" => $jwsSignature,
            "seriesContingencyIndicator" => $request->seriesContingencyIndicator
        ];
        
        try {
            DB::beginTransaction();
            $URL = "/solicitarSerie";
            $response = $this->submitFiscalDocument($invoice, $URL, $submissionUUID);

            $data = json_decode($response->getContent(), true);

            $errorList = data_get($data, 'response.errorList', []);

            $errorList = array_filter($errorList); // remove "", null, false

            if (!empty($errorList)) {

                DB::rollBack();

                $messages = array_map(function ($error) {
                    return isset($error['descriptionError']) && $error['descriptionError']
                        ? $error['idError'] . " - " . $error['descriptionError']
                        : 'Erro desconhecido';
                }, $errorList);

                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao solicitar série: ' . implode(', ', $messages),
                    'errors' => $messages,
                ], 422);
            }

            DB::table('series')->insert([
                'submissionUUID' => $submissionUUID,
                'seriesCode' => $data['response']['seriesFEResult']['seriesCode'],
                'authorizedQuantity' => $data['response']['seriesFEResult']['authorizedQuantity'],
                'firstDocumentNo' => $data['response']['seriesFEResult']['firstDocumentNo'],
                'lastDocumentNo' => $data['response']['seriesFEResult']['lastDocumentNo'],
                'taxRegistrationNumber' => $taxRegistrationNumber,
                // 'taxRegistrationNumber' => $this->taxRegistrationNumber,
                'seriesYear' => $request->seriesYear,
                'documentType' => $request->documentType,
                'establishmentNumber' => $request->establishmentNumber,
                'user_id' => Auth::user()->id,
                'entidade_id' => Auth::user()->entidade_id,
                'seriesContingencyIndicator' => $request->seriesContingencyIndicator
            ]);

            DB::commit();
            return $response;
        } catch (RequestException $e) {
            DB::rollBack();
            return $this->errorSubmitFiscalDocument($e);
        }

        return response()->json([
            'success' => true,
            'message' => 'Operação realizada com sucesso'
        ], 200);
    }

    // public function edit($id)
    // {
    //     $user = auth()->user();

    //     if (!$user->can('editar todos')) {
    //         return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
    //     }

    //     return Serie::findOrFail($id);
    // }

    // public function update(Request $request, $id)
    // {
    //     $user = auth()->user();

    //     if (!$user->can('editar todos')) {
    //         return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
    //     }

    //     $serie = Serie::findOrFail($id);
    //     $serie->update($request->all());
    //     return $serie;
    // }

    // public function destroy($id)
    // {
    //     $user = auth()->user();

    //     if (!$user->can('eliminar todos') && !$user->can('eliminar banco')) {
    //         return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
    //     }

    //     Serie::destroy($id);
    //     return response()->json(['success' => true]);
    // }
}
