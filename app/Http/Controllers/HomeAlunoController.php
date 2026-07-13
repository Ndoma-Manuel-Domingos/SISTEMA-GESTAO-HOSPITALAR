<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AlunoConteudo;
use App\Models\AnuncioProva;
use App\Models\ConteudoAlunoProfessor;
use App\Models\Matricula;
use App\Models\Prova;
use App\Models\Role;
use App\Models\TurmaAluno;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class HomeAlunoController extends Controller
{
    public function dashboard()
    {
    
        $user = auth()->user();
        
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
        $alunoTurmas = TurmaAluno::with(['turma.curso.modulos', 'turma.sala', 'turma.turno', 'turma.formadores.formador'])->where('aluno_id', $entidade->aluno->id)->get();
        
        
        
        $head = [
            "titulo" => "Dashboard Aluno",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "alunoTurmas" => $alunoTurmas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];
        
        return view('alunos.dashboard', $head);
    }
    
        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function privacidade()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $utilizador = User::findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Utilizador",
            "descricao" => env('APP_NAME'),
            "utilizador" => $utilizador,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.privacidade', $head);
    }   
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function privacidade_store(Request $request)
    {
        
        $request->validate([
            'senha' => 'required|string',
            'nova_senha' => 'required|min:3|max:20', //s|same:password
            'confirmar_senha' => 'required|min:3|max:20',
        ]);
                
        if (!Hash::check($request->senha, Auth::user()->password)) {
            Alert::warning("Alerta!", "Senha actual invalída!");
            return redirect()->route('alunos-privacidade');
        }   
                  
        if ($request->nova_senha != $request->confirmar_senha) {
            Alert::warning("Alerta!", "Nova Senha e confirmação da nova senha não conferem!");
            return redirect()->route('alunos-privacidade');
        } 
        
        $user = User::findOrFail(Auth::user()->id);
        $user->password = Hash::make($request->nova_senha);
        $user->login_access = 1;
        $user->update();
        
        Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
        return redirect()->back();
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dados($id)
    {        
        $user = auth()->user();
        
        $utilizador = User::with(['roles'])->findOrFail($id);

        $head = [
            "titulo" => "Utilizador",
            "descricao" => env('APP_NAME'),
            "utilizador" => $utilizador,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.edit', $head);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dados_update(Request $request, $id)
    {
        $user = auth()->user();
        
        $request->validate([
            'nome' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->nome;
        $user->email = $request->email;
        
        $user->update();

        Alert::success("Sucesso!", "Dados Actualizados com Sucesso!");
        return redirect()->back();
  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_conteudo()
    {
        //
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
 
        $turmas_ids = TurmaAluno::where('aluno_id', $entidade->aluno->id)->pluck('turma_id');
        
        $uploads = Video::with(['formador', 'turma', 'entidade', 'user'])
        ->where('entidade_id', $entidade->empresa->id)
        ->whereIn('turma_id', $turmas_ids)
        ->where('type', 'pdf')
        ->get();
        
        $head = [
            "titulo" => "Conteúdos",
            "descricao" => env('APP_NAME'),
            "uploads" => $uploads,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.index-conteudo', $head);
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aluno_conteudo()
    {
        //
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);

        $turmas_ids = TurmaAluno::where('aluno_id', $entidade->aluno->id)->pluck('turma_id');
        
        $hoje = Carbon::now()->toDateString(); // Data atu
        
        $anuncios = ConteudoAlunoProfessor::with(['turma', 'formador'])
        ->whereDate('data_final', '>=', $hoje)
        ->whereDate('data_inicio', '<=', $hoje)
        ->whereIn('turma_id', $turmas_ids)->orderBy('created_at', 'desc')->get();
       
        $head = [
            "titulo" => "Conteúdos Aluno",
            "descricao" => env('APP_NAME'),
            "anuncios" => $anuncios,
            "entidade" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.aluno-conteudo', $head);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aluno_enviar_conteudo($id)
    {
        //
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
 
        $conteudo = ConteudoAlunoProfessor::findOrFail($id);
       
        $head = [
            "titulo" => "Conteúdos Aluno",
            "descricao" => env('APP_NAME'),
            "conteudo" => $conteudo,
            "entidade" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.aluno-enviar-conteudo', $head);
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aluno_enviar_conteudo_post(Request $request)
    {
        //
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $extension = NULL;
            
            if($request->hasFile('arquivo') && $request->file('arquivo')->isValid()){
                $requestImage = $request->arquivo;
                $extension = $requestImage->extension();
                
                $imageName = $requestImage->getClientOriginalName() . strtotime("now") . "." . $extension;
    
                $request->arquivo->move(public_path('videos'), $imageName);
            }else{
                $imageName = NULL;
            }
            
            if($extension == 'pdf'){
                $xet = 'pdf';
            }else {
                $xet = 'video';
            }
            
            $aluno = AlunoConteudo::create([
                'nome' => $request->nome,
                'status' => 'pendente',
                'descricao' => $request->descricao,
                'arquivo' => $imageName,
                'aluno_id' => $entidade->aluno->id,
                'conteudo_id' => $request->conteudo_id,
                'type' => $xet,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_video(Request $request)
    {
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
        
        $turmas_ids = TurmaAluno::where('aluno_id', $entidade->aluno->id)->pluck('turma_id');
        
        $uploads = Video::with(['formador', 'turma', 'entidade', 'user'])
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->whereIn('turma_id', $turmas_ids)
        ->where('type', 'video')
        ->get();
  
        $head = [
            "titulo" => "Vídeos",
            "descricao" => env('APP_NAME'),
            "uploads" => $uploads,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.index-video', $head);
    }
    
    public function provas()
    {
        //
        $entidade = User::with(['empresa', 'formador'])->findOrFail(Auth::user()->id);
        
        $turmas_ids = TurmaAluno::where('aluno_id', $entidade->aluno->id)->pluck('turma_id');
                        
        $hoje = Carbon::now()->toDateString(); // Data atu
        
        $provas = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes'])->where("entidade_id", $entidade->empresa->id)
        ->whereDate('data_final_prova', '<=', $hoje)
        ->whereDate('data_at', '>=', $hoje)
        ->whereIn('turma_id', $turmas_ids)
        ->orderBy('created_at', 'desc')
        ->get();
        
        $head = [
            "titulo" => "Provas",
            "descricao" => env('APP_NAME'),
            "provas" => $provas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.provas', $head);
    }
    
    public function anuncios()
    {
        //
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
        $turmas_ids = TurmaAluno::where('aluno_id', $entidade->aluno->id)->pluck('turma_id');
        
        $anuncios = AnuncioProva::with(['prova', 'turma', 'formador'])->whereIn('turma_id', $turmas_ids)->orderBy('created_at', 'desc')->get();
        
        $head = [
            "titulo" => "Anuncios",
            "descricao" => env('APP_NAME'),
            "anuncios" => $anuncios,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.anuncios', $head);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function provas_detalhe($id)
    {
        $prova = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes'])->findOrFail($id);
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "prova" => $prova,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('alunos.prova-detalhes', $head);    
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function provas_resposta(Request $request)
    {
    
        dd($request->all());
    
        // $prova = Prova::with(['formador', 'turma', 'entidade', 'user', 'questoes'])->findOrFail($id);
        
        // $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        // $head = [
        //     "titulo" => __('messages.mais_detalhes'),
        //     "descricao" => env('APP_NAME'),
        //     "prova" => $prova,
        //     "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        // ];

        // return view('alunos.prova-detalhes', $head);    
    }
    
    
    
    public function matriculas()
    {
        //
        $entidade = User::with(['empresa', 'aluno'])->findOrFail(Auth::user()->id);
 
        $matriculas = Matricula::with(['aluno', 'curso', 'sala', 'turno', "ano_lectivo"])
            ->where('aluno_id', '=', $entidade->aluno->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $head = [
            "titulo" => "Matrículas",
            "descricao" => env('APP_NAME'),
            "matriculas" => $matriculas,
        ];
    
        return view('alunos.matriculas', $head);
    }

}
