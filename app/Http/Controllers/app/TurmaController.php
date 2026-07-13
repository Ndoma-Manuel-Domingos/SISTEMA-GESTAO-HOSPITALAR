<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Sala;
use App\Models\Turma;
use App\Models\Matricula;
use App\Models\Turno;
use App\Models\AnoLectivo;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Estoque;
use App\Models\Formador;
use App\Models\Funcionario;
use App\Models\Imposto;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Marca;
use App\Models\Motivo;
use App\Models\Pauta;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\TurmaAluno;
use App\Models\TurmaFormador;
use App\Models\User;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TurmaController extends Controller
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

        if (!$user->can('listar todos') && !$user->can('listar turma')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $turmas = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Turma",
            "descricao" => env('APP_NAME'),
            "turmas" => $turmas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.index', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar turma')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $salas = Sala::where('entidade_id', $entidade->empresa->id)->get();
        $cursos = Curso::where('entidade_id', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "salas" => $salas,
            "cursos" => $cursos,
            "turnos" => $turnos,
            "anos_lectivos" => $anos_lectivos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar turma')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'curso_id' => 'required',
            'sala_id' => 'required',
            'turno_id' => 'required',
            'ano_lectivo_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $turma = Turma::create([
                'nome' => $request->nome,
                'status' => $request->status,
                'user_id' => Auth::user()->id,
                'curso_id' => $request->curso_id,
                'sala_id' => $request->sala_id,
                'turno_id' => $request->turno_id,
                'ano_lectivo_id' => $request->ano_lectivo_id,
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

        return response()->json(['success' => true, 'message' => "Dados actualizados com sucesso!"], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function turma_adicionar_aluno_store(Request $request)
    {
        //
        $request->validate([
            'aluno_id' => 'required',
            'turma_id' => 'required',
            'matricula_id' => 'required',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $turma = Turma::findOrFail($request->turma_id);
        $aluno = Cliente::findOrFail($request->aluno_id);
        $matricula = Matricula::findOrFail($request->matricula_id);

        if ($turma->curso_id == $matricula->curso_id && $turma->turno_id == $matricula->turno_id) {

            $verificar = TurmaAluno::where([
                'turma_id' => $turma->id,
                'matricula_id' => $matricula->id,
                'aluno_id' => $aluno->id,
                'ano_lectivo_id' => $matricula->ano_lectivo_id,
            ])->first();

            if ($verificar) {
                return redirect()->route('turma-adicionar-aluno', $aluno->id)->with("warning", "Este aluno já está nesta turma!");
            }

            $adicionar = TurmaAluno::create([
                'status' => 'ACTIVO',
                'user_id' => Auth::user()->id,
                'turma_id' => $turma->id,
                'matricula_id' => $matricula->id,
                'aluno_id' => $aluno->id,
                'ano_lectivo_id' => $matricula->ano_lectivo_id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            if ($adicionar->save()) {
                return response()->json(['message' => "Aluno adicionado com sucesso!"], 200);
            }
        } else {
            return response()->json(['message' => "Este turma não foi preparada para receber este aluno, o curso de turno da matricula não corresponde com o curso e turno da turma!!"], 404);
        }
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

        if (!$user->can('listar todos') && !$user->can('listar turma')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);

        $formadores = TurmaFormador::with(['formador'])->where('turma_id', $turma->id)->get();

        $alunos = TurmaAluno::with(['aluno'])->where('turma_id', $turma->id)->get();

        $pautas = Pauta::where('turma_id', $turma->id)->get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "formadores" => $formadores,
            "alunos" => $alunos,
            "pautas" => $pautas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.show', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_distribuir_pautas($id)
    {
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);

            $alunos = TurmaAluno::with(['aluno'])->where('turma_id', $turma->id)->get();

            foreach ($alunos as $item) {
                $verificar = Pauta::where('aluno_id', $item->aluno->id)
                    ->where('turma_id', $turma->id)
                    ->where('entidade_id', $turma->entidade_id)
                    ->first();

                if (!$verificar) {
                    $pauta = Pauta::create([
                        'aluno_id' => $item->aluno->id,
                        'turma_id' => $turma->id,
                        'user_id' => Auth::user()->id,
                        'prova_1' => 0,
                        'prova_2' => 0,
                        'prova_3' => 0,
                        'status' => 'DESACTIVO',
                        'media' => 0,
                        'exame' => 0,
                        'resultado' => 'Nao Definido',
                        'ano_lectivo_id' => $turma->ano_lectivo_id,
                        'entidade_id' => $turma->entidade_id,
                    ]);
                }
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

        return response()->json(['message' => "Dados Actualizados com Sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_visualizar_pautas($id)
    {
        //
        $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);

        $pautas = Pauta::with(['turma', 'aluno', 'ano_lectivo', 'entidade', 'user'])
            ->where('turma_id', $turma->id)
            ->get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "pautas" => $pautas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.pautas', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_lancamento_pautas($id)
    {
        //
        $pauta = Pauta::with(['turma', 'aluno'])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "pauta" => $pauta,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.lancamento-nota', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_lancamento_pautas_store(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $pauta = Pauta::with(['turma', 'aluno'])->findOrFail($request->pauta_id);

            $pauta->prova_1 = $request->prova_1;
            $pauta->prova_2 = $request->prova_2;
            $pauta->prova_3 = $request->prova_3;

            $pauta->status = "ACTIVO";

            $media = ($request->prova_1 + $request->prova_2 + $request->prova_3) / 3;

            $pauta->media = $media;
            $pauta->exame = $request->exame;

            if ($media >= 10) {
                $resultado = "Aprovado";
            } else {
                $resultado = "Reprovado";
            }

            $pauta->resultado = $resultado;

            $pauta->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados actualizados com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_adicionar_aluno($aluno_id)
    {
        //
        $aluno = Cliente::findOrFail($aluno_id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $turmas = Turma::with(['curso', 'sala', 'turno'])->where('entidade_id', $entidade->empresa->id)->get();
        $alunos = Cliente::where('entidade_id', $entidade->empresa->id)->get();
        $matriculas = Matricula::with(['curso', 'sala', 'turno'])->where('aluno_id', $aluno->id)->where('entidade_id', $entidade->empresa->id)->get();


        $head = [
            "titulo" => "Adicionar Aluno á Turma",
            "descricao" => env('APP_NAME'),
            "aluno" => $aluno,
            "turmas" => $turmas,
            "alunos" => $alunos,
            "matriculas" => $matriculas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.adicionar-aluno', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function turma_adicionar_formador($formador_id)
    {
        //
        $formador = Funcionario::findOrFail($formador_id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $turmas = Turma::with(['curso', 'sala', 'turno'])->where('entidade_id', $entidade->empresa->id)->get();
        $formadores = Funcionario::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Adicionar Formador á Turma",
            "descricao" => env('APP_NAME'),
            "formador" => $formador,
            "turmas" => $turmas,
            "formadores" => $formadores,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.adicionar-formador', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function turma_adicionar_formador_store(Request $request)
    {
        //
        $request->validate([
            'formador_id' => 'required',
            'turma_id' => 'required',
        ]);
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $turma = Turma::findOrFail($request->turma_id);
            $formador = Funcionario::findOrFail($request->formador_id);

            $verificar = TurmaFormador::where([
                'turma_id' => $turma->id,
                'formador_id' => $formador->id,
                'ano_lectivo_id' => $turma->ano_lectivo_id,
            ])->first();

            if ($verificar) {
                return redirect()->route('turma-adicionar-formador', $formador->id)->with("warning", "Este Formador já está nesta turma!");
            }

            $adicionar = TurmaFormador::create([
                'status' => 'ACTIVO',
                'user_id' => Auth::user()->id,
                'turma_id' => $turma->id,
                'formador_id' => $formador->id,
                'ano_lectivo_id' => $turma->ano_lectivo_id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Formador adicionado com sucesso!"], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar turma')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $turma = Turma::with(['curso', 'turno', 'sala', 'ano_lectivo'])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $salas = Sala::where('entidade_id', $entidade->empresa->id)->get();
        $cursos = Curso::where('entidade_id', $entidade->empresa->id)->get();
        $turnos = Turno::where('entidade_id', $entidade->empresa->id)->get();
        $anos_lectivos = AnoLectivo::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "turma",
            "descricao" => env('APP_NAME'),
            "turma" => $turma,
            "salas" => $salas,
            "turnos" => $turnos,
            "cursos" => $cursos,
            "anos_lectivos" => $anos_lectivos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.turmas.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar turma')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'curso_id' => 'required',
            'sala_id' => 'required',
            'turno_id' => 'required',
            'ano_lectivo_id' => 'required',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $turma = Turma::findOrFail($id);

            $turma->nome = $request->nome;
            $turma->status = $request->status;
            $turma->curso_id = $request->curso_id;
            $turma->sala_id = $request->sala_id;
            $turma->turno_id = $request->turno_id;
            $turma->ano_lectivo_id = $request->ano_lectivo_id;
            $turma->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados actualizados com sucesso!"], 200);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar sala')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $turma = Turma::findOrFail($id);
        $turma->delete();

        return response()->json(['message' => "Dados Excluídos com sucesso!"], 200);
    }
}
