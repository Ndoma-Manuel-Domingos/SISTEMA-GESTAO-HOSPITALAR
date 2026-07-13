<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Quarto;
use App\Models\Andar;
use App\Models\Internamento;
use App\Models\ItemReserva;
use App\Models\Leito;
use App\Models\Produto;
use App\Models\QuartoTarefario;
use App\Models\TipoQuarto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class QuartoController extends Controller
{

    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar quarto')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $quartos = Quarto::with(['tipo', 'andar', 'quartos.tarefario'])->where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $andares = Andar::where('entidade_id', $entidade->empresa->id)->get();
        $tipos = TipoQuarto::where('entidade_id', $entidade->empresa->id)->get();
        $leitos = Leito::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Quartos",
            "descricao" => env('APP_NAME'),
            "tipos" => $tipos,
            "andares" => $andares,
            "quartos" => $quartos,
            "leitos" => $leitos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.quartos.index', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar quarto')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $inicio = (int) $request->inicio ?? 1;

            for ($i = $inicio; $i < $request->quantidade; $i++) {
                Quarto::create([
                    'entidade_id' => $entidade->empresa->id,
                    'nome' => $request->nome  . "{$i}",
                    'capacidade' => $request->capacidade,
                    'tipo_id' => $request->tipo_id,
                    'andar_id' => $request->andar_id,
                    'descricao' => $request->descricao  . " {$i}",
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

        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function associar($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar tarefario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $tarefarios = Produto::where("aplicado", "Y")->where('entidade_id', $entidade->empresa->id)->get();

        $quarto = Quarto::with(['quartos.tarefario'])->findOrFail($id);

        $head = [
            "titulo" => "Associar Tarifários",
            "descricao" => env('APP_NAME'),
            "tarefarios" => $tarefarios,
            "quarto" => $quarto,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.quartos.associar-tarefaros', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function associar_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar tarefario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'quarto_id' => 'required|string',
            'tarefario_id' => 'required|array', // Garante que quarto_id é um array
            'tarefario_id.*' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $quarto = Quarto::with(['quartos.tarefario'])->findOrFail($request->quarto_id);

            if (!empty($request->tarefario_id) && isset($request->tarefario_id)) {
                foreach ($request->tarefario_id as $key) {
                    $verificar = QuartoTarefario::where('tarefario_id', $key)->where('quarto_id', $quarto->id)->first();
                    if (!$verificar) {
                        QuartoTarefario::create([
                            'tarefario_id' => $key,
                            'quarto_id'  => $quarto->id,
                            'entidade_id' => $entidade->empresa->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
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

        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
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

        if (!$user->can('listar todos') && !$user->can('listar quarto')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $quarto = Quarto::findOrFail($id);
        $quarto->status = 'activo';
        $quarto->update();

        return redirect()->back()->with("success", "Quarto activado com sucesso!");
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

        if (!$user->can('listar todos') && !$user->can('listar quarto')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $quarto = Quarto::findOrFail($id);
        $quarto->status = 'desactivo';
        $quarto->update();

        return redirect()->back()->with("success", "Quarto desactivado com sucesso!!");
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visualizacao_andares_quartos()
    {
        $user = auth()->user();

        if (!$user->can('monitoramento quartos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $andares = Andar::with(["quartos"])->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Monitoramento de Andares e quartos",
            "descricao" => env('APP_NAME'),
            "andares" => $andares,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.quartos.visualizacoes', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visualizacao_andares_quartos_detalhes($id)
    {
        $user = auth()->user();

        if (!$user->can('monitoramento quartos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $quarto = Quarto::findOrFail($id);
        $reservaAtiva = ItemReserva::with(['reserva.tipo_reserva', 'reserva.user', 'reserva.cliente', 'quarto'])->where('code', $quarto->code)->first();

        if (!$reservaAtiva) {
            return redirect()->route('quartos.visualizacao-andares-quartos')->with('error', "Não existe reserva activa para este quarto!");
        }

        $hoje = now();
        $dataEntrada = \Carbon\Carbon::parse($reservaAtiva->reserva->data_inicio);
        $dataSaida = \Carbon\Carbon::parse($reservaAtiva->reserva->data_final);

        $diasTotais = $dataEntrada->diffInDays($dataSaida);
        $diasPassados = $dataEntrada->diffInDays($hoje);
        $diasRestantes = max(0, $diasTotais - $diasPassados);

        $head = [
            "titulo" => "Mais detalhe do Quarto",
            "descricao" => env('APP_NAME'),
            "quarto" => $quarto,
            "reservaAtiva" => $reservaAtiva,
            "diasTotais" => $diasTotais,
            "diasPassados" => $diasPassados,
            "diasRestantes" => $diasRestantes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.quartos.visualizacoes-detalhes', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visualizacao_leitos_quartos(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('monitoramento quartos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $quartos = Quarto::with(["leitos"])->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $search = $request->search;

        $quartos = Quarto::with([
            'leitos.internamento.paciente'
        ])
            ->when($search, function ($query) use ($search) {
                $query->where('nome', 'like', "%$search%")
                    ->orWhereHas('leitos', function ($q) use ($search) {
                        $q->where('nome', 'like', "%$search%")
                            ->orWhereHas(
                                'internamento.paciente',
                                function ($p) use ($search) {
                                    $p->where('nome', 'like', "%$search%")
                                        ->orWhere('nif', 'like', "%$search%");
                                }
                            );
                    });
            })
            ->get();

        $internados = Internamento::whereNull('data_alta')->count();
        $leitosLivres = Leito::whereDoesntHave('internamento')->count();
        $totalLeitos = Leito::count();
        $ocupacao = $totalLeitos > 0  ? round(($internados * 100) / $totalLeitos) : 0;
        $altasHoje = Internamento::whereDate('data_alta', today())->count();

        $head = [
            "titulo" => "Monitoramento de quartos e Leitos",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "internados" => $internados,
            "leitosLivres" => $leitosLivres,
            "ocupacao" => $ocupacao,
            "altasHoje" => $altasHoje,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.quartos.visualizacoes-leitos', $head);
    }

    public function visualizacao_leitos_quartos_detalhes(string $id)
    {
        $leito = Leito::with(['internamento.paciente', 'internamento.equipa', 'internamento.plano_internamento'])->findOrFail($id);

        return response()->json($leito);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lista_pacientes_quartos($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar quarto') && !$user->can('monitoramento quartos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $quarto = Quarto::with(["leitos"])->findOrFail($id);

        $leitos = Leito::whereIn("id", [$quarto->id])->pluck("id");

        $internamentos = Internamento::with(["paciente", "leito"])->whereIn("leito_id", $leitos)->get();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "quarto" => $quarto,
            "internamentos" => $internamentos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.quartos.lista-pacientes', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
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

        if (!$user->can('listar todos') && !$user->can('listar quarto') && !$user->can('monitoramento quartos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $quarto = Quarto::with(['tipo', 'andar', 'quartos.tarefario', 'leitos.internamento.paciente', 'leitos.internamento.plano_internamento'])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "quarto" => $quarto,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.quartos.show', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar quarto')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $quarto = Quarto::findOrFail($id);

        return response()->json(['success' => true, 'data' => $quarto], 200);
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
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $user = auth()->user();

            if (!$user->can('editar todos') && !$user->can('editar quarto')) {
                return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            }

            $request->validate([
                'nome' => 'required|string',
            ]);

            $quarto = Quarto::findOrFail($id);
            $quarto->update($request->all());

            $quarto->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar quarto')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $quarto = Quarto::findOrFail($id);
            $quarto->delete();
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
