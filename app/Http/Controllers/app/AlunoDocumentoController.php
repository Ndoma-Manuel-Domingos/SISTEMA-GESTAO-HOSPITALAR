<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\Exercicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AlunoDocumentoController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $user = auth()->user();
            
        if(!$user->can('listar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
        
        $documentos = Documento::where([
            ['aluno_id', '=', $entidade->aluno->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "documentos" => $documentos,
        ];

        return view('alunos.documentos.index', $head);
    }
    
    public function home()
    {
            
        $user = auth()->user();
        
        if(!$user->can('listar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
        
        $documentos = Documento::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "documentos" => $documentos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.documentos.home', $head);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            
        $user = auth()->user();
        
        if(!$user->can('criar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        //
        $user = auth()->user();
   
        $head = [
            "titulo" => "Solicitação de documentos",
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.documentos.create', $head);
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
        
        if(!$user->can('criar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'tipo_documento_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
            
            $documento = Documento::create([
                'entidade_id' => $entidade->empresa->id, 
                'aluno_id' => $entidade->aluno->id, 
                'tipo_documento_id' => $request->tipo_documento_id,
                'descricao' => $request->descricao,
                'user_id' => Auth::user()->id,
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
        
        return redirect()->back()->with("success", "Dados Cadastrados com Sucesso!");
      
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar($id)
    {
            
        $user = auth()->user();
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $user = auth()->user();
    
        $documento = Documento::findOrFail($id);
        $documento->status = 'em processo';
        $documento->update();
        
        return redirect()->back()->with("success", "Documento Entregue com Sucesso!");
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
            
        $user = auth()->user();
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $user = auth()->user();
        
        $documento = Documento::findOrFail($id);
        $documento->status = 'entregue';
        $documento->update();
        
        return redirect()->back()->with("success", "Documento em processo!");

    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $documento = Documento::findOrFail($id);

        $head = [
            "titulo" =>  __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "documento" => $documento,
        ];

        return view('alunos.documentos.edit', $head);
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
        
        if(!$user->can('editar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $request->validate([
                'tipo_documento_id' => 'required|string',
            ]);
    
            $documento = Documento::findOrFail($id);
            $documento->update($request->all());
            
            $documento->update();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->back()->with("success", "Dados Actualizados com Sucesso!");

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
        
        if(!$user->can('eliminar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $documento = Documento::findOrFail($id);
            $documento->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return redirect()->back()->with("success", "Dados Excluído com Sucesso!");

    }

}
