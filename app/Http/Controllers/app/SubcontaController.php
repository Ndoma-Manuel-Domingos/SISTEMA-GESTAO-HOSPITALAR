<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Conta;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SubcontaController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar subconta')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $subcontas = Subconta::with(['user', 'entidade', 'conta'])->where("entidade_id", $entidade->empresa->id)->orderBy('numero', 'asc')->get();
        $contas = Conta::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.subconta'),
            "descricao" => env('APP_NAME'),
            "requests" => $request->all('subconta_id'),
            "subcontas" => $subcontas,
            "contas" => $contas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.subcontas.index', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar subconta')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            if(isset($request->subconta_store_id) && empty($request->subconta_store_id)) {
                
                $request->validate([
                    'nome_subconta' => 'required|string',
                    'subonta_conta_id' => 'required|string',
                    'subconta_store_id' => 'required',
                ]);
                
                $subconta = Subconta::findOrFail($request->subconta_store_id);
                $subconta->numero = $request->subconta_numero;
                $subconta->nome = $request->nome_subconta;
                $subconta->tipo_conta = $request->subconta_tipo_conta;
                $subconta->status = $request->subconta_status;
                $subconta->conta_id = $request->subonta_conta_id;
                $subconta->tipo_operacao = $request->subconta_tipo_operacao;
                $subconta->update();
                
            }else {
                    
                $request->validate([
                    'nome' => 'required|string',
                    'conta_id' => 'required|string',
                    'tipo_operacao' => 'required|string',
                ]);
            
                $code = uniqid(time());
    
                $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
    
                $conta = Conta::findOrFail($request->conta_id);
    
                $numero_conta = $conta->conta . "." . $request->numero;
                
                $subconta_ = Subconta::where('conta_id', $conta->id)->where('numero', $numero_conta)->where('entidade_id', $entidade->empresa->id)->first();
    
                if ($subconta_) {
                    return response()->json(['success' => false, 'message' => "Este número da subconta já existe!"], 404);
                }
         
                $subconta = Subconta::create([
                    'entidade_id' => $entidade->empresa->id,
                    'numero' => $numero_conta,
                    'nome' => $request->nome,
                    'tipo_conta' => $request->tipo_conta,
                    'code' => $code,
                    'status' => $request->status,
                    'conta_id' => $request->conta_id,
                    'user_id' => Auth::user()->id,
                ]);
            }

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar subconta')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $subconta = Subconta::with(['entidade', 'user', 'conta'])->findOrFail($id);
        
        return response()->json(['success' => true, 'data' => $subconta], 200);

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
        if (!$user->can('editar todos') && !$user->can('editar subconta')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'conta_id' => 'required|string',
        ]);

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $subconta = Subconta::findOrFail($id);

            $subconta->entidade_id = $entidade->empresa->id;
            $subconta->numero = $request->numero;
            $subconta->nome = $request->nome;
            $subconta->tipo_conta = $request->tipo_conta;
            $subconta->status = $request->status;
            $subconta->conta_id = $request->conta_id;
            $subconta->update();

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
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar subconta')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $subconta = Subconta::findOrFail($id);
            $subconta->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluído com sucesso!"], 200);

    }
}
