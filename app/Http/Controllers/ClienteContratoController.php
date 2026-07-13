<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteContrato;
use App\Models\ContratoPosto;
use App\Models\Entidade;
use App\Models\Equipa;
use App\Models\TipoPagamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ClienteContratoController extends Controller
{

    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $contratos = ClienteContrato::with(['cliente', 'forma_pagamento', 'user', 'entidade'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
        ->get();

        $empresa = Entidade::findOrFail($entidade->empresa->id);
      
        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "contratos" => $contratos,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contratos.index', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes",'clientes',"marcas","categorias"])->findOrFail($entidade->empresa->id);
        
        $clientes = Cliente::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "tipos_pagamentos" => $tipos_pagamentos,
            "clientes" => $clientes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contratos.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'codigo_contrato' => 'required|string',
            'cliente_id' => 'required|string',
            'estado' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa.tipo_entidade'])->findOrFail(Auth::user()->id);

            $contrato = ClienteContrato::create([
                "codigo_contrato" => $request->codigo_contrato,
                "cliente_id" => $request->cliente_id,
                "status" => $request->estado,
                "valor_mensal" => $request->valor_mensal,
                "data_inicio" => $request->data_inicio,
                "data_final" => $request->data_final,
                "forma_pagamento_id" => $request->forma_pagamento_id,
                "descricao" => $request->descricao,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $contrato = ClienteContrato::with(["cliente", "forma_pagamento", "user", "entidade", "postos"])->findOrFail($id);
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes",'clientes',"marcas","categorias"])->findOrFail($entidade->empresa->id);
        
        $equipas = Equipa::where('entidade_id', $entidade->empresa->id)->get();
        $postos = ContratoPosto::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" =>  __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "contrato" => $contrato,
            "postos" => $postos,
            "equipas" => $equipas,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.clientes.contratos.show", $head);
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

        if (!$user->can("editar todos") || !$user->can("editar cliente")) {
            return redirect()->back()->with("danger", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $contrato = ClienteContrato::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes",'clientes',"marcas","categorias"])->findOrFail($entidade->empresa->id);
        
        $clientes = Cliente::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_pagamentos = TipoPagamento::get();
        
        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "tipos_pagamentos" => $tipos_pagamentos,
            "clientes" => $clientes,
            "contrato" => $contrato,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contratos.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar cliente')) {
            return redirect()->back();
        }

        $request->validate([
            'codigo_contrato' => 'required|string',
            'cliente_id' => 'required|string',
            'estado' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $contrato = ClienteContrato::findOrFail($id);
            $contrato->codigo_contrato = $request->codigo_contrato;
            $contrato->cliente_id = $request->cliente_id; 
            $contrato->status = $request->estado; 
            $contrato->valor_mensal = $request->valor_mensal;
            $contrato->data_inicio = $request->data_inicio; 
            $contrato->data_final = $request->data_final;
            $contrato->forma_pagamento_id = $request->forma_pagamento_id; 
            $contrato->descricao = $request->descricao; 

            $contrato->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar cliente')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $contrato = ClienteContrato::findOrFail($id);
            $contrato->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados Excluido com sucesso!'], 200);
    }


}
