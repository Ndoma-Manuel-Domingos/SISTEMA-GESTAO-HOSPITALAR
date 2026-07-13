<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\DisponibilidadeMedica;
use App\Models\Entidade;
use App\Models\Medico;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class DisponibilidadeMedicaController extends Controller
{

    use TraitChavesSaft;
    use TraitHelpers;

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $disponibilidades = DisponibilidadeMedica::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_inicio', $value);
        })
            ->with(['medico', 'entidade', 'user'])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $medicos = Medico::with(['funcionario.user_principal'])->where('entidade_id', $entidade->empresa->id)->get();

        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Disponíbilidade Médica",
            "descricao" => env('APP_NAME'),
            "medicos" => $medicos,
            "disponibilidades" => $disponibilidades,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.disponibilidades.index', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function agenda(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $disponibilidades = DisponibilidadeMedica::when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_inicio', $value);
        })
            ->with(['medico', 'entidade', 'user'])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $medicos = Medico::with(['funcionario.user_principal'])->where('entidade_id', $entidade->empresa->id)->get();

        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Agenda Médica",
            "descricao" => env('APP_NAME'),
            "medicos" => $medicos,
            "disponibilidades" => $disponibilidades,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.disponibilidades.agenda-medica', $head);
    }


    public function calendario(Request $request)
    {
        $eventos = [];

        if ($request->disponibilidade) {
            $lista = DisponibilidadeMedica::where('medico_id', $request->medico_id)
                ->whereBetween('data_inicio',  [$request->inicio, $request->fim])
                ->get();

            foreach ($lista as $d) {
                $cor = match ($d->estado) {
                    'Disponível' => '#28a745',
                    'Indisponível' => '#6c757d',
                    'Férias' => '#dc3545',
                    'Licença' => '#fd7e14',
                    'Congresso' => '#6f42c1',
                    'Outros' => '#17a2b8',
                    default => '#343a40'
                };
                $eventos[] = [
                    'id' => 'disp_' . $d->id,
                    'title' => $d->estado,
                    'start' => $d->data_inicio,
                    'end' => $d->data_fim,
                    'backgroundColor' => $cor,
                    'borderColor' => $cor
                ];
            }
        }

        if ($request->consultas) {
            $consultas = Consulta::with('paciente')
                ->where('medico_id', $request->medico_id)
                ->whereBetween('data_consulta', [$request->inicio, $request->fim])
                ->get();

            foreach ($consultas as $consulta) {
                $eventos[] = [
                    'id' => 'consulta_' . $consulta->id,
                    'title' => $consulta->paciente->nome . "/consulta",
                    'start' => $consulta->data_consulta,
                    'end' => $consulta->data_consulta,
                    'backgroundColor' => '#007bff',
                    'borderColor' => '#007bff'
                ];
            }
        }

        return response()->json($eventos);
    }


    public function eventos(Request $request)
    {
        $inicio = $request->start;
        $fim = $request->end;

        $dados = DisponibilidadeMedica::with('medico.funcionario')
            ->whereBetween('data_inicio', [$inicio, $fim])
            ->get();

        $eventos = [];

        foreach ($dados as $d) {

            $cor = match ($d->estado) {
                'Disponível'    => '#28a745', // Verde
                'Indisponível' => '#6c757d', // Cinza
                'Férias'        => '#dc3545', // Vermelho
                'Licença'       => '#fd7e14', // Laranja
                'Congresso'     => '#6f42c1', // Roxo
                'Outros'        => '#17a2b8', // Azul claro
                default         => '#343a40'
            };

            $eventos[] = [
                'id' => $d->id,
                'title' => $d->medico->funcionario->nome . ' - ' . $d->estado,
                'start' => $d->data_inicio,
                'end' => $d->data_fim,
                'color' => $cor
            ];
        }
        return response()->json($eventos);
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

        $request->validate([
            "medico_id" => "required",
            "inicio" => "required",
            "fim" => "required",
            "estado" => "required",
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            //$this->validarDataDisponibilidade($request);

            $dataInicio = Carbon::parse($request->inicio);
            $dataFim = Carbon::parse($request->fim);

            DisponibilidadeMedica::create([
                'medico_id' => $request->medico_id,
                'estado' => $request->estado,
                'data_inicio'  => $dataInicio,
                'data_fim'     => $dataFim,
                'observacao' => $request->obs,
                'entidade_id' => $entidade->empresa->id,
                'user_id' => Auth::user()->id,
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

        return response()->json([
            'success' => true
        ], 200);
    }

    private function validarDataDisponibilidade(Request $request)
    {
        $dataInicio = Carbon::parse($request->inicio);
        $dataFim = Carbon::parse($request->fim);

        if ($dataInicio->isPast()) {
            throw ValidationException::withMessages([
                'inicio' => 'Não é permitido cadastrar uma disponibilidade em uma data passada.'
            ]);
        }

        if ($dataFim->lessThanOrEqualTo($dataInicio)) {
            throw ValidationException::withMessages([
                'fim' => 'A data final deve ser superior à data inicial.'
            ]);
        }
    }

    public function show(string $id)
    {
        return DisponibilidadeMedica::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function drop(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        $request->validate([
            "inicio" => "required",
            "fim" => "required",
        ]);

        $disponibilidade = DisponibilidadeMedica::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $dataInicio = Carbon::parse($request->inicio);
            $dataFim = Carbon::parse($request->fim);

            $disponibilidade->data_inicio = $dataInicio;
            $disponibilidade->data_fim = $dataFim;

            $disponibilidade->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json([
            'success' => true
        ], 200);
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

        if (!$user->can('editar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "medico_id" => "required",
            "inicio" => "required",
            "fim" => "required",
            "estado" => "required",
        ]);

        $disponibilidade = DisponibilidadeMedica::findOrFail($id);

        $this->validarDataDisponibilidade($request);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $dataInicio = Carbon::parse($request->inicio);
            $dataFim = Carbon::parse($request->fim);

            $disponibilidade->estado = $request->estado;
            $disponibilidade->data_inicio = $dataInicio;
            $disponibilidade->data_fim = $dataFim;
            $disponibilidade->medico_id = $request->medico_id;
            $disponibilidade->observacao = $request->obs;

            $disponibilidade->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json([
            'success' => true
        ], 200);
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

            $disponiblidade = DisponibilidadeMedica::findOrFail($id);
            $disponiblidade->delete();

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
