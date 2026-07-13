<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ModuloController extends Controller
{    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $modulo_entidade = Modulo::orderBy('created_at', 'desc')->get();

       $head = [
           "titulo" => "Modulos Entidade",
           "descricao" => env('APP_NAME'),
           "modulos_entidade" => $modulo_entidade,
           "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
       ];

       return view('admin.modulos_entidade.index', $head);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
       //
       $head = [
           "titulo" => __('messages.novo'),
           "descricao" => env('APP_NAME'),
           "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
       ];

       return view('admin.modulos_entidade.create', $head);
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
       $request->validate([
           'modulo' => 'required',
       ]);
        
        try {
            DB::beginTransaction();
            
            $modulo_entidade = Modulo::updateOrCreate([
               'modulo' => $request->modulo,
               'tipo' => $request->tipo,
               'descricao' => $request->descricao,
            ], [
               'modulo' => $request->modulo,
            ]);  
            
            $modulo_entidade->save();
            
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
       $modulo_entidade = Modulo::findOrFail($id);
       
       $head = [
           "titulo" => __('messages.mais_detalhes'),
           "descricao" => env('APP_NAME'),
           "modulo_entidade" => $modulo_entidade,
           "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
       ];

       return view('admin.modulos_entidade.show', $head);
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
       $modulo_entidade = Modulo::findOrFail($id);

       $head = [
           "titulo" => "Modulo Entidade",
           "descricao" => env('APP_NAME'),
           "modulo_entidade" => $modulo_entidade,
           "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
       ];

       return view('admin.modulos_entidade.edit', $head);
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
       $request->validate([
           'modulo' => 'required|string',
       ]);
        
        try {
            DB::beginTransaction();
    
            $modulo_entidade = Modulo::findOrFail($id);
            $modulo_entidade->modulo = $request->modulo;
            $modulo_entidade->tipo = $request->tipo;
            $modulo_entidade->descricao = $request->descricao;
            
            $modulo_entidade->update();
            
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
       try {
            DB::beginTransaction();
            $modulo_entidade = Modulo::findOrFail($id);
            $modulo_entidade->delete();
        
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
}
