<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\Camara;
use App\Models\Gaveta;
use App\Models\Internamento;
use App\Models\Morgue;
use App\Models\MorgueLiberacao;
use App\Models\Obito;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use Ramsey\Uuid\Uuid;

use PDF;
use phpseclib\Crypt\RSA;


class MorgueController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $morgues = Morgue::with(["obito", "gaveta", "camara", "user", "entidade"])->where("entidade_id", $entidade->entidade_id)->get();

        $head = [
            "titulo" => "Morgues",
            "descricao" => env("APP_NAME"),
            "morgues" => $morgues,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.morgues.index", $head);
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $camaras = Camara::where('entidade_id', $entidade->empresa->id)
            ->get();

        $gavetas = Gaveta::where('ocupacao', 0)->where('entidade_id', $entidade->empresa->id)
            ->get();

        $obitos = Obito::where('entidade_id', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env("APP_NAME"),
            "camaras" => $camaras,
            "gavetas" => $gavetas,
            "obitos" => $obitos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.morgues.create", $head);
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

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'data_entrada_morgue' => 'required|date',
            'hora_entrada_morgue' => 'required',
            'gaveta_id' => 'required',
            'camara_id' => 'required',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $verificar_obito = Morgue::where("obito_id", $request->obito_id)->first();
            if(!$verificar_obito) {
                Morgue::create([
                    "obito_id" => $request->obito_id,
                    "data_entrada_morgue" => $request->data_entrada_morgue,
                    "hora_entrada_morgue" => $request->hora_entrada_morgue,
                    "data_liberacao" => $request->data_liberacao,
                    "hora_liberacao" => $request->hora_liberacao,
                    "gaveta_id" => $request->gaveta_id,
                    "camara_id" => $request->camara_id,
                    "temperatura_armazenamento" => $request->temperatura_armazenamento,
                    "observacoes_iniciais" => $request->observacoes_iniciais,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
                
                $gaveta = Gaveta::findOrFail($request->gaveta_id);
                $gaveta->ocupacao = 1;
                $gaveta->update();
            } else {
                return response()->json(['success' => true, 'message' => "Este obito já esta registrado!"], 404);
            }


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning("Informação", $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function entregar_funeraria($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {

            $morgue = Morgue::findOrFail($id);
            $morgue->status = "entregue";
            $morgue->update();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function liberacao_morgue_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $request->validate([
            'morgue_registro_id' => 'required',
            'data_liberacao' => 'required',
            'nome_responsavel_retirada' => 'required',
        ]);
       
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            MorgueLiberacao::create([
                "morgue_registro_id" => $request->morgue_registro_id,
                "data_liberacao" => $request->data_liberacao,
                "hora_liberacao" => $request->hora_liberacao,
                "nome_responsavel_retirada" => $request->nome_responsavel_retirada,
                "documento_responsavel" => $request->documento_responsavel,
                "relacionamento" => $request->relacionamento,
                "empresa_funeraria" => $request->empresa_funeraria,
                "observacoes" => $request->observacoes,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);
            
            $morgues = Morgue::findOrFail($request->morgue_registro_id);
            $morgues->data_liberacao = $request->data_liberacao;
            $morgues->hora_liberacao = $request->hora_liberacao;
            $morgues->status = "liberado";
            $morgues->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning("Informação", $e->getMessage());
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
        $morgue = Morgue::with(["obito", "gaveta", "camara", "user", "entidade", "liberacao"])->findOrFail($id);
        
        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "morgue" => $morgue,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.morgues.show', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function morgue_liberacao_imprimir($id)
    {
    
        $liberacao = MorgueLiberacao::with(["morgue.obito", "user", "entidade"])->findOrFail($id);
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            "titulo" => "Imprimir Ficha Liberação",
            "descricao" => env('APP_NAME'),
            "liberacao" => $liberacao,
            
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.morgues.ficha-liberacao', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function morgue_imprimir($id)
    {
        $morgue = Morgue::with(["obito", "gaveta", "camara", "user", "entidade", "liberacao"])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            "titulo" => "Imprimir Ficha Registro",
            "descricao" => env('APP_NAME'),
            "morgue" => $morgue,
            
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.morgues.ficha-morgue', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function liberacao_morgue($id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $morgue = Morgue::with(["obito", "gaveta", "camara", "user", "entidade"])->findOrFail($id);

        $head = [
            "titulo" => "Liberação a funerária",
            "descricao" => env("APP_NAME"),
            "entidade" => $entidade,
            "morgue" => $morgue,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.morgues.liberacao", $head);
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

        if (!$user->can('editar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $morgue = Morgue::with(["obito", "gaveta", "camara", "user", "entidade"])->findOrFail($id);

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $camaras = Camara::where('entidade_id', $entidade->empresa->id)
            ->get();

        $gavetas = Gaveta::where('entidade_id', $entidade->empresa->id)
            ->get();

        $obitos = Obito::where('entidade_id', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env("APP_NAME"),
            "morgue" => $morgue,
            "camaras" => $camaras,
            "gavetas" => $gavetas,
            "obitos" => $obitos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.morgues.edit", $head);
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

        if (!$user->can('editar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'data_entrada_morgue' => 'required|date',
            'hora_entrada_morgue' => 'required',
            'gaveta_id' => 'required',
            'camara_id' => 'required',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $morgue = Morgue::findOrFail($id);

            $morgue->obito_id = $request->obito_id;
            $morgue->data_entrada_morgue = $request->data_entrada_morgue;
            $morgue->hora_entrada_morgue = $request->hora_entrada_morgue;
            $morgue->data_liberacao = $request->data_liberacao;
            $morgue->hora_liberacao = $request->hora_liberacao;
            $morgue->gaveta_id = $request->gaveta_id;
            $morgue->camara_id = $request->camara_id;
            $morgue->temperatura_armazenamento = $request->temperatura_armazenamento;
            $morgue->observacoes_iniciais = $request->observacoes_iniciais;

            $morgue->update();

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

        if (!$user->can('eliminar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $morgue = Morgue::findOrFail($id);

            $gaveta = Gaveta::findOrFail($morgue->gaveta_id);
            $gaveta->ocupacao = 0;
            $gaveta->update();

            $morgue->delete();

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
