<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\AnuncioAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use function Ramsey\Uuid\v1;

class AnuncioAdminController extends Controller
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

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $anuncios = AnuncioAdmin::where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "anuncios" => $anuncios,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.anuncios.index', $head);
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
        
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.anuncios.create', $head);
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
            'titulo' => 'required|string',
            'descricao' => 'required|string',
            'image1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validação do arquivo
            'image2' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validação do arquivo
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $users = User::where('entidade_id', $entidade->empresa->id)->get();
        
        try {
            DB::beginTransaction();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            
            if($request->hasFile('image1') && $request->file('image1')->isValid()){
                $requestImage = $request->image1;
                $extension = $requestImage->extension();
    
                $imageName1 = $requestImage->getClientOriginalName() . strtotime("now") . "." . $extension;
    
                $request->image1->move(public_path('images/anuncios'), $imageName1);
            }else{
                $imageName1 = NULL;
            }
            
            if($request->hasFile('image2') && $request->file('image2')->isValid()){
                $requestImage = $request->image2;
                $extension = $requestImage->extension();
    
                $imageName2 = $requestImage->getClientOriginalName() . strtotime("now") . "." . $extension;
    
                $request->image2->move(public_path('images/anuncios'), $imageName2);
            }else{
                $imageName2 = NULL;
            }
            
            $anuncio = AnuncioAdmin::create([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'origem' => 'Entidade',
                'image1' => $imageName1,
                'image2' => $imageName2,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            $anuncio->save();    
            
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"]);

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
            
        if(!$user->can('listar todos')){
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $anuncio = AnuncioAdmin::findOrFail($id);
        
        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "anuncio" => $anuncio,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.anuncios.show', $head);

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
        
        $anuncio = AnuncioAdmin::findOrFail($id);

        $head = [
            "titulo" =>  __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "anuncio" => $anuncio,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('admin.anuncios.edit', $head);
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
        
        $request->validate([
            'titulo' => 'required|string',
        ]);
                
        try {
            DB::beginTransaction();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
    
            $anuncio = AnuncioAdmin::findOrFail($id);
            $anuncio->titulo = $request->titulo;
            $anuncio->descricao = $request->descricao;
            $anuncio->status = $request->status;
            $anuncio->update();
            
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"]);
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
        
        try {
            DB::beginTransaction();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            
            $anuncio = AnuncioAdmin::findOrFail($id);
            $anuncio->delete();
            
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"]);
        
    }

}
