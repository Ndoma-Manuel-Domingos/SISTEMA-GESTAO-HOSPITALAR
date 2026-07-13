<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\Conta;
use App\Models\Entidade;
use App\Models\ItemVenda;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Subconta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class CaixaController extends Controller
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

        if (!$user->can('listar todos')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::findOrFail($entidade->empresa->id);

        $caixas = Caixa::where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')
            ->where('status_admin', 'liberado')->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "caixas" => $caixas,
            "entidade" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar todos')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        if (!isset($request->createLoja)) {
            return redirect()->route('lojas.index');
        }

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "loja_id" => $request->createLoja,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.create', $head);
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
            'nome' => 'required|string',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());
            $code_c = uniqid(time() + 1);
            $nova_conta = "";

            $conta = Conta::where('conta', '45')->where('entidade_id', $entidade->empresa->id)->first();
            
            $serie =  "45.1.";

            if ($request->tipo_caixa == "T1") {
                $serie =  "45.1.";
            }
            if ($request->tipo_caixa == "T2") {
                $serie =  "45.2.";
            }
            if ($request->tipo_caixa == "T3") {
                $serie =  "45.3.";
            }


            $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
            $nova_conta =  $serie . "{$subc_}";

            $subconta = Subconta::create([
                'entidade_id' => $entidade->empresa->id,
                'numero' => $nova_conta,
                'nome' => $request->nome,
                'tipo_conta' => 'M',
                'code' => $code,
                'status' => $conta->status ?? "desactivo",
                'conta_id' => $conta->id,
                'user_id' => Auth::user()->id,
            ]);
            
            // Caixa principal
            $caixas = Caixa::create([
                'nome' => $request->nome,
                'conta' => $nova_conta,
                'code' => $code,
                'code_caixa' => $code_c,
                'status' => $request->status ?? "desactivo",
                'user_id' => Auth::user()->id,
                "loja_id" => $request->loja_id,
                'entidade_id' => $entidade->empresa->id,
                'subconta_id' => $subconta->id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd( $e->getMessage());
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
    public function show($id, Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $caixa = Caixa::findOrFail($id);

        $movimentos = OperacaoFinanceiro::where("subconta_id", $caixa->subconta_id)
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("date_at", ">=", Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("date_at", "<=", Carbon::createFromDate($value));
            })
            ->when($request->operador_id, function ($query, $value) {
                $query->where("user_id", $value);
            })
            ->where("entidade_id", $entidade->empresa->id)
            ->with(["user", "centro_custo", "subconta"])
            ->orderBy("id", "desc")
            ->get();

        $utilizadores = User::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "caixa" => $caixa,
            "movimentos" => $movimentos,
            "dados" => $entidade,
            "utilizadores" => $utilizadores,
            "requests" => $request->all("data_inicio", "data_final", "operador_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.show', $head);
    }


    public function movimentos_caixa(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        if ($request->documento_pdf === "exportar_pdf") {
            $movimentos = ItemVenda::with(['caixa', 'produto', 'user', 'venda.cliente'])
                ->where('entidade_id', $entidade->empresa->id)
                // ->whereIn('status', ['realizado'])
                ->whereHas('factura', function ($query) {
                    $query->whereIn('status_factura', ['pago', 'anulada']);
                })
                ->when($request->data_inicio, function ($query, $value) {
                    $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
                })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
                })
                ->when($request->caixa_id, function ($query, $value) {
                    $query->where('caixa_id', '=', $value);
                })
                ->when($request->user_id, function ($query, $value) {
                    $query->where('user_id', '=', $value);
                })
                ->get();
        } else {
            $movimentos = OperacaoFinanceiro::when($request->caixa_id, function ($query, $value) {
                $query->where("subconta_id", $value);
            })
                ->when($request->data_inicio, function ($query, $value) {
                    $query->whereDate("date_at", ">=", Carbon::createFromDate($value));
                })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate("date_at", "<=", Carbon::createFromDate($value));
                })
                ->when($request->operador_id, function ($query, $value) {
                    $query->where("user_id", $value);
                })
                ->where("entidade_id", $entidade->empresa->id)
                ->with(["user", "centro_custo", "subconta"])
                ->where("entidade_id", $entidade->empresa->id)
                ->get();
        }


        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        $users = User::where("entidade_id", $entidade->empresa->id)->get();
        $caixas = Caixa::where("entidade_id", $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Movimentos do caixa",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "movimentos" => $movimentos,
            "users" => $users,
            "caixas" => $caixas,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final', 'operador_id', 'caixa_id'),
            "user" => User::find($request->operador_id),
            "caixa" => Caixa::find($request->caixa_id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        if ($request->documento_pdf === "exportar_pdf") {
            $pdf = PDF::loadView('dashboard.vendas.caixas.movimentos-pdf', $head);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view('dashboard.vendas.caixas.movimentos', $head);
        }
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

        if (!$user->can('editar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $caixa = Caixa::findOrFail($id);

        $head = [
            "titulo" =>  __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "caixa" => $caixa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.caixas.edit', $head);
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
            'nome' => 'required|string',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());
            $nova_conta = "";

            $caixa = Caixa::findOrFail($id);

            $conta = Conta::where('conta', '45')->where('entidade_id', $entidade->empresa->id)->first();
            if ($request->tipo_caixa == "T1") {
                $serie =  "45.1.";
            }
            if ($request->tipo_caixa == "T2") {
                $serie =  "45.2.";
            }
            if ($request->tipo_caixa == "T3") {
                $serie =  "45.3.";
            }

            if ($caixa->code == NULL) {
                $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                $nova_conta =  $serie . "{$subc_}";

                $subconta = Subconta::create([
                    'entidade_id' => $entidade->empresa->id,
                    'numero' => $nova_conta,
                    'nome' => $request->nome,
                    'tipo_conta' => 'M',
                    'code' => $code,
                    'status' => $conta->status,
                    'conta_id' => $conta->id,
                    'user_id' => Auth::user()->id,
                ]);

                $caixa->conta = $nova_conta;
                $caixa->code = $code;
                $caixa->subconta_id = $subconta->id;
            }

            if ($caixa->tipo_caixa != $request->tipo_caixa) {
                $subconta = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                $nova_conta =  $serie . "{$subconta}";

                if ($subconta) {
                    $subc_up = Subconta::findOrFail($caixa->subconta_id);
                    $subc_up->numero = $nova_conta;
                    $subc_up->nome = $request->nome;
                    $subc_up->code = $code;
                    $subc_up->update();
                }

                $caixa->conta = $nova_conta;
                $caixa->code = $code;
            }

            $caixa->nome = $request->nome;
            $caixa->status = $request->status;
            $caixa->tipo_caixa = $request->tipo_caixa;
            $caixa->documento_predefinido = $request->documento_predefinido;
            $caixa->aspecto = $request->aspecto;
            $caixa->metodo_impressao = $request->metodo_impressao;
            $caixa->modelo = $request->modelo;
            $caixa->impressao_papel = $request->impressao_papel;
            $caixa->modelo_email = $request->modelo_email;
            $caixa->finalizar_avancado = $request->finalizar_avancado;
            $caixa->referencia_produtos = $request->referencia_produtos;
            $caixa->precos_produtos = $request->precos_produtos;
            $caixa->modo_funcionamento = $request->modo_funcionamento;
            $caixa->listar_produtos = $request->listar_produtos;
            $caixa->grupo_precos = $request->grupo_precos;
            $caixa->numeracao_pedidos_mesa = $request->numeracao_pedidos_mesa;
            $caixa->update();

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

        if (!$user->can('editar todos')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $caixa = Caixa::findOrFail($id);
            $caixa->delete();
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
