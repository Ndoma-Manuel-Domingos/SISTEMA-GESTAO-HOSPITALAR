<?php

namespace App\Http\Controllers;

use App\Models\ContratoPosto;
use App\Models\Entidade;
use App\Models\Equipa;
use App\Models\Funcionario;
use App\Models\HorarioFuncionario;
use App\Models\MembroEquipa;
use App\Models\PostoRecurso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;
use phpseclib\Crypt\RSA;

class ConstruirEquipaController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $equipas = Equipa::with(["membros", "responsavel"])
            ->where("entidade_id", $entidade->empresa->id)
            ->orderBy("created_at", "desc")
            ->get();

        $empresa = Entidade::with(["variacoes", "clientes", "marcas", "categorias"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Equipas",
            "descricao" => env("APP_NAME"),
            "equipas" => $equipas,
            "empresa" => $empresa,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.equipas.index", $head);
    }

    public function create(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $profissionais = Funcionario::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Constituir Equipa",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "profissionais" => $profissionais,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipas.create', $head);
    }

    public function buscarEscala(Request $request)
    {
        $horarios = HorarioFuncionario::with(['posto', 'funcionario'])->where('funcionario_id', $request->funcionario_id)->get();
        return response()->json($horarios);
    }

    public function create_recursos(Request $request)
    {
        $request->validate([
            'recurso_id' => 'required|array',
            'posto_id' => 'required'
        ]);

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            foreach ($request->recurso_id as $item) {
                $verificar_recurso = PostoRecurso::where("recurso_id", $item)
                    ->where("posto_id", $request->posto_id)
                    ->where("entidade_id", $entidade->empresa->id)
                    ->first();

                if (!$verificar_recurso) {
                    PostoRecurso::create([
                        "recurso_id"   => $item,
                        "descricao"  => $request->descricao,
                        "posto_id"    => $request->posto_id,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
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

        return response()->json(['message' => 'Dados Excluido com sucesso!'], 200);
    }

    public function destroy_recursos($equipeId)
    {
        try {
            DB::beginTransaction();

            // Realizar operações de banco de dados aqui
            $horario = PostoRecurso::findOrFail($equipeId);
            $horario->delete();

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

    public function create_horario(Request $request)
    {
        $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'dia_semana' => 'required',
        ]);

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificar_horario = HorarioFuncionario::where("dia_semana", $request->dia_semana)
                ->where("funcionario_id", $request->funcionario_id)
                ->where("posto_id", $request->posto_id)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();


            if (!$verificar_horario) {
                try {
                    HorarioFuncionario::create([
                        "funcionario_id"   => $request->funcionario_id,
                        "dia_semana"  => $request->dia_semana,

                        "data_inicio" => $request->data_entrada,
                        "data_fim"    => $request->data_saida,

                        "hora_inicio" => $request->hora_entrada,
                        "hora_fim"    => $request->hora_saida,

                        "turno"       => $this->definirTurno($request->hora_entrada),
                        "posto_id"    => $request->posto_id,
                        "tipo"        => "disponivel",
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                } catch (\Exception $ee) {
                    dd($ee->getMessage());
                }
            } else {
                return response()->json(['message' => 'Este funcionário já tem este horário registrado!'], 200);
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados Excluido com sucesso!'], 200);
    }

    public function update_horario(Request $request, $id)
    {
        $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'dia_semana' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $horario = HorarioFuncionario::findOrFail($id);

            $horario->funcionario_id = $request->funcionario_id;
            $horario->dia_semana     = $request->dia_semana;
            $horario->hora_inicio    = $request->hora_entrada;
            $horario->hora_fim       = $request->hora_saida;
            $horario->data_inicio    = $request->data_entrada;
            $horario->data_fim       = $request->data_saida;
            $horario->turno          = $this->definirTurno($request->hora_entrada);
            $horario->posto_id       = $request->posto_id;
            $horario->tipo           = "disponivel";
            $horario->update();

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

    public function destroy_medico_equipa($equipeId)
    {
        $membro = MembroEquipa::findOrFail($equipeId);
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $membro->delete();
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

    public function destroy_horario($equipeId)
    {
        try {
            DB::beginTransaction();

            // Realizar operações de banco de dados aqui
            $horario = HorarioFuncionario::findOrFail($equipeId);
            $horario->delete();

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

    private function definirTurno($horaInicio)
    {
        $hora = (int) explode(':', $horaInicio)[0];

        if ($hora >= 6 && $hora < 12) return 'manhã';
        if ($hora >= 12 && $hora < 18) return 'tarde';
        return 'noite';
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adicionarMembros(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $membro = Funcionario::findOrFail($request->profissional_id);

            $verificar_membro = MembroEquipa::where('profissional_id', $membro->id)
                ->where('equipa_id', NULL)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_membro) {
                MembroEquipa::create([
                    'profissional_id' => $membro->id,
                    'equipa_id' => NULL,
                    'cargo' => $request->cargo,
                    'status' => "processo",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
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

        $query = MembroEquipa::with(["profissional"])->whereNull('equipa_id')
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->count();

        return response()->json(["items" => $items, "total" => $total], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMembros($id)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $membro = MembroEquipa::findOrFail($id);
        $membro->delete();

        $query = MembroEquipa::with(["profissional"])
            ->whereNull('equipa_id')
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->count();

        return response()->json(["items" => $items, "total" => $total], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "nome" => "required",
        ]);

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $membros = MembroEquipa::with(["profissional"])->whereNull("equipa_id")
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->get();

            $total = 0;

            foreach ($membros as $item) {
                $total++;
            }

            $equipa = Equipa::create([
                "nome" => $request->nome,
                "status" => "desactiva",
                "area_atuacao" => $request->area_atuacao,
                "responsavel_id" => $request->responsavel_id,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            foreach ($membros as $item) {
                $item_ = MembroEquipa::findOrFail($item->id);
                $item_->equipa_id = $equipa->id;
                $item_->status = "concluido";
                $item_->update();
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["message" => "Consulta registrada com sucesso!", "equipa" => $equipa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $equipa = Equipa::with(["membros"])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $profissionais = Funcionario::where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "profissionais" => $profissionais,
            "equipa" => $equipa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipas.edit', $head);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizarMembros(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $membro = Funcionario::findOrFail($request->profissional_id);
        $equipa = Equipa::findOrFail($request->equipa_id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificar_items = MembroEquipa::where('profissional_id', $membro->id)
                ->where('equipa_id', $equipa->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_items) {
                MembroEquipa::create([
                    'profissional_id' => $membro->id,
                    'equipa_id' => $equipa->id,
                    'cargo' => $request->cargo,
                    'status' => "processo",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
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

        $query = MembroEquipa::with(["profissional"])
            ->where('equipa_id', $equipa->id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->count();

        return response()->json(["items" => $items, "total" => $total], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteActualizarMembros($id, $equipa_id)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $membro = MembroEquipa::findOrFail($id);
        $membro->delete();

        $query = MembroEquipa::with(["profissional"])
            ->where('equipa_id', $equipa_id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();
        $total = $query->count();

        return response()->json(["items" => $items, "total" => $total], 200);
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

        $request->validate([
            "nome" => "required",
        ]);

        $equipa = Equipa::findOrFail($id);
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $items = MembroEquipa::with(["profissional"])->where("equipa_id", $equipa->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->get();

            $total = 0;

            foreach ($items as $item) {
                $total++;
            }

            $equipa->nome = $request->nome;
            $equipa->responsavel_id = $request->responsavel_id;
            $equipa->area_atuacao = $request->area_atuacao;

            foreach ($items as $item) {
                $item_ = MembroEquipa::findOrFail($item->id);
                $item_->equipa_id = $equipa->id;
                $item_->status = "concluido";
                $item_->update();
            }

            $equipa->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["message" => "equipa registrada com sucesso!", "equipa" => $equipa]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $equipa = Equipa::findOrFail($id);
            $equipa->delete();

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $equipe  = Equipa::with(["membros.profissional.horarios"])->findOrFail($id);
        $postos = ContratoPosto::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" =>  __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "equipe" => $equipe,
            "postos" => $postos,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipas.show', $head);
    }
}
