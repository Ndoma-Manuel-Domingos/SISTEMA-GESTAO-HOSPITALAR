<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\ConsultaItem;
use App\Models\ExameItem;
use App\Models\Internamento;
use App\Models\Medico;
use App\Models\Prioridade;
use App\Models\Produto;
use App\Models\SolicitacaoMedica;
use App\Models\SolicitacaoMedicaItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;
use phpseclib\Crypt\RSA;

class SolicitacaoMedicaController extends Controller
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

        if (!$user->can('listar todos') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $data = date("Y-m-d");

        $solicitacoes = SolicitacaoMedica::when($request->status, function ($query, $value) {
            $query->where('status', $value);
        })
            ->whereDate('created_at', $data)
            ->with(['prioridade', 'atendimento', 'paciente', 'medico', 'items.produto.categoria'])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Solicitações Médicas",
            "descricao" => env('APP_NAME'),
            "solicitacoes" => $solicitacoes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.exames.solicitacoes.index', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $user = auth()->user();
        if (!$user->can('criar todos') && !$user->can('monitoramento consultorio') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $categoria = Categoria::whereIn('categoria', ['Exames', 'Exame', 'exames', 'exame', 'Consultas', 'Consulta', 'consultas', 'consulta'])->where('entidade_id', $entidade->empresa->id)->pluck("id");

        $produtos = Produto::whereIn('categoria_id', $categoria)->where('entidade_id', $entidade->empresa->id)
            ->get();

        $prioridades = Prioridade::where('entidade_id', $entidade->empresa->id)
            ->get();

        $origem = null;

        if ($request->origem == "internamento") {
            $origem = Internamento::find($request->internamento_id);
        } else
        if ($request->origem == "atendimento") {
            $origem = Atendimento::find($request->atendimento_id);
        } else if ($request->origem == "consulta") {
            $origem = Consulta::find($request->consulta_id);
        }

        $medicos = Medico::with(["funcionario"])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pacientes = Cliente::when($origem ? $origem->cliente_id : "", function ($query, $value) {
            $query->where('id', $value);
        })
            ->when($origem ? $origem->paciente_id : "", function ($query, $value) {
                $query->where('id', $value);
            })
            ->when($request->paciente_id, function ($query, $value) {
                $query->where('id', $value);
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Solitações Exames & Consultas",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "produtos" => $produtos,
            "medicos" => $medicos,
            "origem" => $origem,
            "request_ordem" => $request->origem,
            "prioridades" => $prioridades,
            "pacientes" => $pacientes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.exames.solicitacoes.create', $head);
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
        if (!$user->can('criar todos') && !$user->can('monitoramento consultorio') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            "paciente_id" => "required",
            "prioridade_id" => "required",
            "tipo" => "required",
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            $items = SolicitacaoMedicaItem::with(["produto.categoria"])->whereNull("solicitacao_medica_id")
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->get();

            $atendimento = Atendimento::findOrFail($request->origem_id);

            if ($request->tipo == "exame") {
                $sigla = "SOLIC/EXAME-";
            } else {
                $sigla = "SOLIC/CONSULTA-";
            }

            $solicitacao = $sigla . time();

            $solicitacao_ = SolicitacaoMedica::create([
                "solicitacao" => $solicitacao,
                "paciente_id" => $request->paciente_id,
                "justificativa" => $request->justificativa,
                "prioridade_id" => $request->prioridade_id,
                "atendimento_id" => $atendimento->id,
                "medico_id" => NULL,
                "tipo" => $request->tipo,
                "user_id" => Auth::user()->id,
                "entidade_id" =>  $entidade->empresa->id,
                "observacao" => $request->observacao,
            ]);

            foreach ($items as $item) {
                $item_ = SolicitacaoMedicaItem::findOrFail($item->id);
                $item_->solicitacao_medica_id = $solicitacao_->id ?? NULL;
                $item_->status = "concluido";
                $item_->update();
            }

            $atendimento = Atendimento::findOrFail($atendimento->id);
            $atendimento->status = "aguardando";
            $atendimento->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["message" => "Solicitação registrada com sucesso!", "Solicitação" => $solicitacao_]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adicionarItems(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('criar todos') && !$user->can('monitoramento consultorio') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $produto = Produto::findOrFail($request->item_id);

            $verificar_items = SolicitacaoMedicaItem::where('produto_id', $produto->id)
                ->where('solicitacao_medica_id', NULL)
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$verificar_items) {
                SolicitacaoMedicaItem::create([
                    'produto_id' => $produto->id,
                    'solicitacao_medica_id' => NULL,
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
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        $query = SolicitacaoMedicaItem::with(["produto.categoria"])->whereNull('solicitacao_medica_id')
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();

        return response()->json(["items" => $items], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteItems($id)
    {
        $user = auth()->user();
        if (!$user->can('criar todos') && !$user->can('monitoramento consultorio') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $item = SolicitacaoMedicaItem::findOrFail($id);
        $item->delete();

        $query = SolicitacaoMedicaItem::with(["produto.categoria"])->whereNull('solicitacao_medica_id')
            ->where('user_id', Auth::user()->id)
            ->where('entidade_id', $entidade->empresa->id);

        $items = $query->get();

        return response()->json(["items" => $items], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirmarItem(Request $request)
    {

        $user = auth()->user();
        if (!$user->can('criar todos') && !$user->can('monitoramento consultorio') && !$user->can('consultorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'solicitacao_id' => 'required',
            'itens' => 'array',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
            $solicitacao = SolicitacaoMedica::with(['items'])->findOrFail($request->solicitacao_id);

            $atendimento = Atendimento::with(['contaHospitalar'])->findOrFail($solicitacao->atendimento_id);

            if ($solicitacao->tipo == "exame") {

                if ($solicitacao->items) {
                    foreach ($solicitacao->items as $item) {
                        $produto = Produto::findOrFail($item->produto_id);

                        $verificar_items = ExameItem::where('produto_id', $produto->id)
                            ->where('exame_id', NULL)
                            ->where('user_id', Auth::user()->id)
                            ->where('entidade_id', $entidade->empresa->id)
                            ->first();

                        if (!$verificar_items) {
                            ExameItem::create([
                                'produto_id' => $produto->id,
                                'exame_id' => NULL,
                                'valor' => $produto->preco_venda,
                                'status' => "processo",
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                            ]);
                        }
                    }
                }
            }

            if ($solicitacao->tipo == "consulta") {

                if ($solicitacao->items) {

                    foreach ($solicitacao->items as $item) {
                        $produto = Produto::findOrFail($item->produto_id);

                        $verificar_items = ConsultaItem::where('produto_id', $produto->id)
                            ->where('consulta_id', NULL)
                            ->where('user_id', Auth::user()->id)
                            ->where('entidade_id', $entidade->empresa->id)
                            ->first();

                        if (!$verificar_items) {
                            ConsultaItem::create([
                                'produto_id' => $produto->id,
                                'consulta_id' => NULL,
                                'valor' => $produto->preco_venda,
                                'status' => "processo",
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                            ]);
                        }
                    }
                }
            }

            $solicitacao->status = "agendado";
            $solicitacao->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        if ($solicitacao->tipo === 'exame') {
            return response()->json([
                'redirect' => route('exames.create', ['origem' => 'atendimento', 'atendimento_id' => $atendimento->id])
            ]);
        }

        return response()->json([
            'redirect' => route('consultas.create', ['origem' => 'atendimento', 'atendimento_id' => $atendimento->id])
        ]);
    }

    public function carregarItens(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        if ($request->tipo === "exames") {
            $query = ExameItem::with(["produto.categoria"])->whereNull('exame_id')
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id);

            $items = $query->get();
            $total = $query->sum("valor");

            return response()->json(["items" => $items, "total" => $total], 200);
        } else {

            $query = ConsultaItem::with(["produto.categoria"])->whereNull('consulta_id')
                ->where('user_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id);

            $items = $query->get();
            $total = $query->sum("valor");

            return response()->json(["items" => $items, "total" => $total], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $solicitacoes = SolicitacaoMedica::when($request->status, function ($query, $value) {
            $query->where('status', $value);
        })
            ->with(['prioridade', 'atendimento', 'paciente', 'medico', 'items.produto.categoria'])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => 'Solicitações Médicas',
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env('APP_NAME'),
            "solicitacoes" => $solicitacoes,
            "requests" => $request->all("status"),
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.exames.solicitacoes.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
