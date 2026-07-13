<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\FacturaSeguradora;
use App\Models\Municipio;
use App\Models\Seguradora;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SeguradoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $seguradoras = Seguradora::where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "seguradoras",
            "descricao" => env('APP_NAME'),
            "seguradoras" => $seguradoras,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.index', $head);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        $paciente = Cliente::where('id', $request->paciente_id)->first();

        return Seguradora::when($paciente->seguradora_id, function ($query, $value) {
            $query->where('id', $value);
        })
            ->where('entidade_id', auth()->user()->entidade_id)
            ->select('id', 'nome')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        //
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $seguradora = Seguradora::create([
                'nome' => $request->nome,
                'nome_fantasia' => $request->nome_fantasia,
                'sigla' => $request->sigla,
                'tipo' => $request->tipo,
                'status' => $request->status,
                'nif' => $request->nif,
                'numero' => $request->numero,
                'email' => $request->email,
                'contacto' => $request->contacto,
                'telefone_secundario' => $request->telefone_secundario,
                'website' => $request->website,
                'pessoa_contato' => $request->pessoa_contato,
                'cidade' => $request->cidade,
                'provincia' => $request->provincia,
                'pais' => $request->pais,
                'observacoes' => $request->observacoes,

                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $startOfDay = Carbon::now()->startOfDay();

        $seguradora = Seguradora::with(['facturas', 'planos'])->findOrFail($id);

        $facturasPagas = FacturaSeguradora::with(['itens'])
            ->whereDate('saldo', '<=', 0)
            ->where('seguradora_id', $seguradora->id)
            ->count();

        $facturas_vencidas = FacturaSeguradora::with(['itens'])
            ->whereDate('data_vencimento', '<', $startOfDay)
            ->where('seguradora_id', $seguradora->id)
            ->count();

        $facturas_correntes = FacturaSeguradora::with(['itens'])
            ->whereDate('data_vencimento', '>=', $startOfDay)
            ->where('seguradora_id', $seguradora->id)
            ->count();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "seguradora" => $seguradora,
            "facturas_vencidas" => $facturas_vencidas,
            "facturas_correntes" => $facturas_correntes,
            "facturasPagas" => $facturasPagas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $seguradora = Seguradora::findOrFail($id);

        $head = [
            "titulo" => "Seguradora",
            "descricao" => env('APP_NAME'),
            "seguradora" => $seguradora,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.edit', $head);
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
        //
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate(['nome' => 'required|string']);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $seguradora = Seguradora::findOrFail($id);

            $seguradora->nome = $request->nome;
            $seguradora->nome_fantasia = $request->nome_fantasia;
            $seguradora->sigla = $request->sigla;
            $seguradora->tipo = $request->tipo;
            $seguradora->status = $request->status;
            $seguradora->nif = $request->nif;
            $seguradora->numero = $request->numero;
            $seguradora->email = $request->email;
            $seguradora->contacto = $request->contacto;
            $seguradora->telefone_secundario = $request->telefone_secundario;
            $seguradora->website = $request->website;
            $seguradora->pessoa_contato = $request->pessoa_contato;
            $seguradora->cidade = $request->cidade;
            $seguradora->provincia = $request->provincia;
            $seguradora->pais = $request->pais;
            $seguradora->observacoes = $request->observacoes;

            $seguradora->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $seguradora = Seguradora::findOrFail($id);
            $seguradora->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }
}
