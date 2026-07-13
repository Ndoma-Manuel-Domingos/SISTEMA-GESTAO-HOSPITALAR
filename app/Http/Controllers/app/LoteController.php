<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\ControloSistema;
use App\Models\Entidade;
use App\Models\License;
use App\Models\Lote;
use App\Models\Produto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar lote')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $lotes = Lote::with(['produto'])->when($request->produto_id, function ($query, $value) {
            $query->where('produto_id', $value);
        })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "lotes",
            "descricao" => env('APP_NAME'),
            "lotes" => $lotes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
            "requests" => $request->all('produto_id'),
        ];


        return view('dashboard.lotes.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar lote')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        //
        $produtos = Produto::when($request->produto_id, function ($query, $value) {
            $query->where('id', $value);
        })->where('entidade_id', '=', $entidade->empresa->id)
            ->where('lote_valicidade', '=', 'Sim')
            ->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "produtos" => $produtos,
            "requests" => $request->all('produto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lotes.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar lote')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $request->validate([
            'produto_id' => 'required|string',
            'lote' => 'required|string',
            'codigo_barra' => 'required|string',
            'data_validade' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            //

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $lotes = Lote::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get();

            if ($request->data_validade < date("Y-m-d")) {
                $status = 'expirado';
            } else {
                $status = 'activo';
            }

            if ($request->associar_id == "Sim") {
                $produto = Produto::findOrFail($request->produto_id);
                $produto->codigo_barra = $request->codigo_barra;
                $produto->update();
            }

            $lote = Lote::create([
                'produto_id' => $request->produto_id,
                'lote' => $request->lote,
                'status' => $status,
                'codigo_barra' => $request->codigo_barra,
                'data_validade' => $request->data_validade,
                'stock_total' => 0,
                'entidade_id' => $entidade->empresa->id,
            ]);

            $lote->save();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar lote')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $lotes = Lote::with(['produto'])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "lote" => $lotes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lotes.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar lote')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        //
        $produtos = Produto::when($request->produto_id, function ($query, $value) {
            $query->where('id', $value);
        })->where([
            ['entidade_id', '=', $entidade->empresa->id],
            ['lote_valicidade', '=', 'Sim'],
        ])->get();

        $lotes = Lote::findOrFail($id);

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "lotes" => $lotes,
            "produtos" => $produtos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.lotes.edit', $head);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar lote')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $request->validate([
            'produto_id' => 'required|string',
            'lote' => 'required|string',
            'codigo_barra' => 'required|string',
            'data_validade' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            if ($request->data_validade < date("Y-m-d")) {
                $request->status = 'expirado';
            } else {
                $request->status = 'activo';
            }

            if ($request->associar_id == "Sim") {
                $produto = Produto::findOrFail($request->produto_id);
                $produto->codigo_barra = $request->codigo_barra;
                $produto->update();
            }

            $lote = Lote::findOrFail($id);
            $lote->produto_id = $request->produto_id;
            $lote->lote = $request->lote;
            $lote->status = $request->status;
            $lote->codigo_barra = $request->codigo_barra;
            $lote->data_validade = $request->data_validade;
            $lote->entrada = $lote->entrada;
            $lote->entrada = $lote->entrada;
            $lote->stock_total = $lote->stock_total;

            $lote->update();
            //
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar lote')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();

            $lote = Lote::findOrFail($id);
            $lote->delete();

            //
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    public function lotesProximosDaValidade()
    {
        $hoje = Carbon::today();
        $limite = Carbon::today()->addDays(4);

        $lotes = Lote::with(["produto"])->whereBetween('data_validade', [$hoje, $limite])->get();

        return response()->json($lotes);
    }


    public function validadeDaLicenca()
    {
        // $user = auth()->user();

        // $this->authenticated($user);

        // $controlo = License::where('activated_for_company_id', $user->entidade_id)->first();

        // dd($controlo, $user->entidade_id);

        // dd($this->diasRestantesLicenca($controlo->end_date), $controlo->end_date);

        // if ($this->diasRestantesLicenca($controlo->end_date) <= 10) {
        //     return response()->json(['success' => true, 'dias_restantes' => $this->diasRestantesLicenca($controlo->end_date)]);
        // }
    }

    protected function diasRestantesLicenca($dataFinal)
    {
        $dataFinal = \Carbon\Carbon::parse($dataFinal)->startOfDay();
        $hoje = \Carbon\Carbon::today();

        // Se a data já passou, retorna 0 ou negativo
        return $hoje->diffInDays($dataFinal, false);
    }

    protected function authenticated(User $user)
    {
        // redirecionar para pagina de ativação se sem licença
        $companyId = $user->entidade_id ?? null;

        if ($companyId) {
            $has = License::where('activated_for_company_id', $companyId)
                ->where('used', true)
                ->where('status', 'active')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->exists();

            if (! $has) {
                auth()->logout();
                return redirect()->route('licenses.upload')->withErrors('Licença não ativa. Faça upload para activar.');
            }
        }
        return redirect()->route('dashboard');
    }
}
