<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\ContaFornecedore;
use App\Models\Dispesa;
use App\Models\EncomendaFornecedore;
use App\Models\Entidade;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\FacturaEncomendaFornecedorPagamento;
use App\Models\Fornecedore;
use App\Models\ItensEncomenda;
use App\Models\Loja;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Subconta;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\UserLoja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;


class FacturaEncomendaFornecedorController extends Controller
{
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();


        if (!$user->can('listar todos') && !$user->can('listar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();

        $query = FacturaEncomendaFornecedor::where('entidade_id', $entidade->empresa->id)
            ->when($request->fornecedor_id, function ($query, $value) {
                $query->where('fornecedor_id', $value);
            })
            ->when($request->filled('status_factura'), function ($query) use ($request) {
                // Converte o parâmetro para booleano
                $status = filter_var($request->input('status_factura'), FILTER_VALIDATE_BOOLEAN);
                $query->where('status', $status);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_factura', ">=", $value);
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_factura', "=<", $value);
            })
            ->with(['fornecedor', 'user', 'encomenda'])
            ->orderBy('created_at', 'asc');


        // contas a pagar dos meses passados
        if ($request->relatorio == "contas_pagar_atraso") {
            $query->where('data_vencimento', '<', now()->startOfMonth());
        }

        // Contas a pagar deste meses
        if ($request->relatorio == "contas_pagar_mes") {
            $query->whereMonth('data_vencimento', now()->month)
                ->whereYear('data_vencimento', now()->year);
        }

        $facturas = $query->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "fornecedores" => $fornecedores,
            "facturas" => $facturas,
            "requests" => $request->all("fornecedor_id", "status_factura", "data_inicio", "data_final"),
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.index', $head);
    }

    public function criarFacturaCompra($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $encomenda = EncomendaFornecedore::findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();

        $totalEncomendas = FacturaEncomendaFornecedor::where('entidade_id', $entidade->empresa->id)
            ->count()  + 1;

        $totalEncomendas = "FT " . date('y') . "" . date('m') . "" . date('d') . "-" . $totalEncomendas;

        $dispesas = Dispesa::where('entidade_id', $entidade->empresa->id)->where('type', 'D')->get();

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "encomenda" => $encomenda,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "dispesas" => $dispesas,
            "totalEncomendas" => $totalEncomendas,
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'factura' => 'required',
            'valor_a_pagar' => 'required',
            'valor_total_factura_original' => 'required',
            'data_factura' => 'required',
            'data_vencimento' => 'required',
            'marcar_como' => 'required',
            'encomenda_id' => 'required',
        ]);

        $encomenda = EncomendaFornecedore::findOrFail($request->encomenda_id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $fornecedor = Fornecedore::findOrFail($encomenda->fornecedor_id);

        $code = uniqid(time());

        if ($request->marcar_como == "sim") {
            $request->validate([
                'forma_pagamento_id' => 'required',
                'dispesa_id' => 'required',
            ]);

            if ($request->forma_pagamento_id == "NU") {
                $request->validate([
                    'caixa_id' => 'required',
                ]);
            }

            if ($request->forma_pagamento_id == "MB" || $request->forma_pagamento_id == "TE" || $request->forma_pagamento_id == "DE") {
                $request->validate([
                    'banco_id' => 'required',
                ]);
            }
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $status2 = "nao concluido";
            $status = false;

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if ($request->marcar_como == "sim") {

                $dispesa = Dispesa::findOrFail($request->dispesa_id);

                if ($request->forma_pagamento_id == "NU") {
                    $caixa = Caixa::findOrFail($request->caixa_id);
                    $verificar_saldo = $this->saldo_conta($caixa->subconta_id);

                    if ($request->valor_a_pagar > $verificar_saldo) {
                        return response()->json(['message' => "Pretende realizar o pagamento das encomendas utilizando os fundos do caixa: {$caixa->conta} - {$caixa->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a este caixa para prosseguir com a transação."], 404);
                    }
                    // $subconta_caixa = Subconta::where('code', $request->caixa_id)->first();

                    #VAMOS CREDITAR NO CAIXA OU SEJA VAMOS TIRAR O DINHEIRO NO CAIXA SELECIONADO PARA PAGAR O FORNECEDOR
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $caixa->subconta_id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $request->valor_a_pagar,
                        'debito' => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => $request->data_factura,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    OperacaoFinanceiro::create([
                        'nome' => $dispesa->nome,
                        'status' => "pago",
                        'formas' => "C",
                        'motante' => $request->valor_a_pagar,
                        'subconta_id' => $caixa->subconta_id,
                        'fornecedor_id' => $fornecedor->id,
                        'model_id' => $dispesa->id,
                        'type' => "D",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'descricao' => $dispesa->nome,
                        'movimento' => "S",
                        'user_open_id' => Auth::user()->id,
                        'date_at' => $request->data_factura,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    ]);
                }

                if ($request->forma_pagamento_id == "MB") {

                    $banco = ContaBancaria::findOrFail($request->banco_id);
                    // $subconta_banco = Subconta::where('code', $request->banco_id)->first();

                    $verificar_saldo = $this->saldo_conta($banco->subconta_id);

                    if ($request->valor_a_pagar > $verificar_saldo) {
                        return response()->json(['message' => "Pretende realizar o pagamento das encomendas utilizando os fundos da conta bancária: {$banco->conta} - {$banco->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a esta conta bancária para prosseguir com a transação."], 404);
                    }

                    #VAMOS CREDITAR NO BANCO OU SEJA VAMOS TIRAR O DINHEIRO NO BANCO SELECIONADO PARA PAGAR O FORNECEDOR
                    $movimeto = Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $banco->subconta_id,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $request->valor_a_pagar,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'debito' => 0,
                        'observacao' => $request->observacao,
                        'code' => $code,
                        'data_at' => $request->data_factura,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    OperacaoFinanceiro::create([
                        'nome' => $dispesa->nome,
                        'status' => "pago",
                        'formas' => "B",
                        'motante' => $request->valor_a_pagar,
                        'subconta_id' => $banco->subconta_id,
                        'fornecedor_id' => $fornecedor->id,
                        'model_id' => $dispesa->id,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'type' => "D",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'descricao' => $dispesa->nome,
                        'movimento' => "S",
                        'user_open_id' => Auth::user()->id,
                        'date_at' => $request->data_factura,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    ]);
                }

                if ($encomenda->descontado == 1) {
                    $total = $request->valor_a_pagar;
                } else {
                    $total = $request->valor_a_pagar + $request->desconto;
                }

                $encomenda->descontado = 1;
                $encomenda->update();

                ## DEBITAR FORNECEDOR - REDUZIR A DIVIDA COM O FORNECEDOR

                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $fornecedor->subconta_id,
                    'status' => true,
                    'movimento' => 'E',
                    'credito' => 0,
                    'debito' => $total,
                    'observacao' => $request->observacao,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'data_at' => $request->data_factura,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                $status2 = "concluido";
                $status = true;

                $encomenda->total_a_pagar -= $request->valor_a_pagar;
                $encomenda->tota_pago += $request->valor_a_pagar;

                if ($encomenda->tota_pago >= $encomenda->total_a_pagar) {
                    $encomenda->status_pagamento = true;
                } else {
                    $encomenda->status_pagamento = false;
                }

                $factura = FacturaEncomendaFornecedor::create([
                    'factura' => $request->factura,
                    'fornecedor_id' => $encomenda->fornecedor_id,
                    'encomenda_id' => $encomenda->id,
                    'user_id' => Auth::user()->id,
                    'desconto' => $request->desconto_imposto,
                    'desconto_valor' => $request->desconto,
                    'valor_factura' => $request->valor_a_pagar,
                    'valor_pago' => $request->valor_a_pagar,
                    'total_pago' => $request->valor_a_pagar ?? 0,
                    'valor_divida' => 0,
                    'data_factura' => $request->data_factura,
                    'data_vencimento' => $request->data_vencimento,
                    'observacao' => $request->observacao,
                    'referenciante' => $encomenda->factura,
                    'data_pagamento' => date("Y-m-d"),
                    'status' => $status,
                    'status2' => $status2,
                    'status3' => "original",
                    'entidade_id' => $entidade->empresa->id,
                ]);

                $encomenda->update();

                $forma_pag = TipoPagamento::where('tipo', $request->forma_pagamento_id)->first();

                $total_pagamentos = FacturaEncomendaFornecedorPagamento::where('factura_id', $factura->id)->where('entidade_id', $entidade->empresa->id)->count() + 1;

                FacturaEncomendaFornecedorPagamento::create([
                    'factura_id' => $factura->id,
                    'forma_pagamento_id' => $forma_pag->id ?? NULL,
                    'data_pagamento' => $request->data_factura,
                    'observacao' => $request->observacao,
                    'valor_pago' => $request->valor_a_pagar,
                    'descricao' => "PN {$total_pagamentos}",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            } else {

                $status2 = "nao concluido";
                $status = false;
                // $divida_encomenda = $encomenda->total_a_pagar - $request->valor_a_pagar;
                $divida_factura = $request->valor_a_pagar;
                $valor_a_pagar_factura = $request->valor_a_pagar;
                $valor_pago = 0;

                FacturaEncomendaFornecedor::create([
                    'factura' => $request->factura,
                    'fornecedor_id' => $encomenda->fornecedor_id,
                    'encomenda_id' => $encomenda->id,
                    'user_id' => Auth::user()->id,
                    'desconto' => $request->desconto_imposto,
                    'desconto_valor' => $request->desconto,
                    'valor_factura' => $valor_a_pagar_factura,
                    'valor_pago' => $valor_pago,
                    'total_pago' => $valor_pago,
                    'valor_divida' => $divida_factura,
                    'data_factura' => $request->data_factura,
                    'data_vencimento' => $request->data_vencimento,
                    'observacao' => $request->observacao,
                    'referenciante' => $encomenda->factura,
                    'data_pagamento' => NULL,
                    'status' => $status,
                    'status2' => $status2,
                    'status3' => "original",
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            $conta = ContaFornecedore::where([
                ['fornecedor_id', '=', $encomenda->fornecedor_id]
            ])->first();

            $upatedConta = ContaFornecedore::findOrFail($conta->id);

            if ($request->marcar_como == "nao") {
                $upatedConta->saldo += $request->valor_a_pagar;
                $upatedConta->divida_corrente += $request->valor_a_pagar;
                $upatedConta->update();
            }

            if ($request->marcar_como == "sim") {
                $upatedConta->saldo -= $request->valor_a_pagar;
                $upatedConta->divida_corrente -= $encomenda->tota_pago;
                $upatedConta->update();
            }

            if ($encomenda->tota_pago > $encomenda->total) {
                return response()->json(['message' => 'O Valor a pagar não pode ser superior ao valor total da factura, verifica as facturas já criadas para estas encomendas, caso ainda não efectuou o pagamento das mesma factura faça-o!'], 404);
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

        return response()->json(['message' => "Factura de Compra criada com sucesso", 'success' => true, 'redirect' => route('fornecedores-encomendas.show', $encomenda->id)]);

        // Alert::success('Sucesso', 'Factura de Compra criada com sucesso');
        // return redirect()->route('fornecedores-encomendas.show', $encomenda->id);
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
        if (!$user->can('listar todos') && !$user->can('listar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $factura = FacturaEncomendaFornecedor::with(['fornecedor', 'user', 'encomenda', 'pagamentos.forma_pagamento'])->findOrFail($id);

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $encomenda = null;
        $items = [];

        if ($factura->encomenda_id) {
            $encomenda = EncomendaFornecedore::with('fornecedor', 'user')->findOrFail($factura->encomenda_id);

            $items = ItensEncomenda::where('code', $encomenda->code)
                ->where('entidade_id', $entidade->empresa->id)
                ->with(['produto'])
                ->get();
        }


        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "factura" => $factura,
            "loja" => $entidade,
            "items" => $items,
            "encomenda" => $encomenda,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.show', $head);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $factura = FacturaEncomendaFornecedor::findOrFail($id);
        $factura->delete();

        Alert::success('Sucesso', 'Factura ou Pagamento Excluído com sucesso!');
        return redirect()->route('fornecedores-encomendas.show', $factura->encomenda->id);
    }

    public function liquidarFacturaCompra($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $factura = FacturaEncomendaFornecedor::with('fornecedor', 'user', 'encomenda')->findOrFail($id);

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $caixas = Caixa::where('entidade_id', $entidade->empresa->id)
            ->where('status_admin', 'liberado')->get();
        $bancos = ContaBancaria::where('entidade_id', $entidade->empresa->id)->get();

        $dispesas = Dispesa::where('entidade_id', $entidade->empresa->id)->where('type', 'D')->get();

        $head = [
            "titulo" => "Liquidar encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "factura" => $factura,
            "loja" => $entidade,
            "caixas" => $caixas,
            "bancos" => $bancos,
            "dispesas" => $dispesas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.liquidar', $head);
    }

    public function liquidarFacturaCompraStore(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'valor_liquidar' => 'required',
            'factura_id' => 'required',
            'data_pagamento' => 'required',
            'observacao' => 'required',
        ]);

        $factura = FacturaEncomendaFornecedor::findOrFail($request->factura_id);
        $fornecedor = Fornecedore::findOrFail($factura->fornecedor_id);
        $encomenda = EncomendaFornecedore::findOrFail($factura->encomenda_id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $code = uniqid(time());

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            $dispesa = Dispesa::findOrFail($request->dispesa_id);

            if ($request->forma_pagamento_id == "") {
                return response()->json(['message' => 'Deves selecionar uma forma de pagamento da factura!'], 404);
            }

            if ($request->forma_pagamento_id == "NU") {
                if ($request->caixa_id == "") {
                    return response()->json(['message' => 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!'], 404);
                }

                $caixa = Caixa::findOrFail($request->caixa_id);

                $verificar_saldo = $this->saldo_conta($caixa->subconta_id);

                if ($request->valor_liquidar > $verificar_saldo) {
                    return response()->json(['message' => "Pretende realizar o pagamento das encomendas utilizando os fundos do caixa: {$caixa->conta} - {$caixa->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a este caixa para prosseguir com a transação."], 404);
                }

                #VAMOS CREDITAR NO CAIXA OU SEJA VAMOS TIRAR O DINHEIRO DO CAIXA SELECIONADO
                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $caixa->subconta_id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $request->valor_liquidar,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'debito' => 0,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                OperacaoFinanceiro::create([
                    'nome' => $dispesa->nome,
                    'status' => "pago",
                    'formas' => "C",
                    'motante' => $request->valor_liquidar,
                    'subconta_id' => $caixa->subconta_id,
                    'fornecedor_id' => $fornecedor->id,
                    'model_id' => $dispesa->id,
                    'type' => "D",
                    'parcelado' => "N",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => $dispesa->nome,
                    'movimento' => "S",
                    'user_open_id' => Auth::user()->id,
                    'date_at' => $request->data_pagamento,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                ]);
            }

            if ($request->forma_pagamento_id == "MB") {
                if ($request->banco_id == "") {
                    return response()->json(['message' => 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!'], 404);
                }

                $banco = ContaBancaria::findOrFail($request->banco_id);
                #VAMOS CREDITAR NO BANCO OU SEJA VAMOS TIRAR O DINHEIRO DO BANCO SELECIONADO

                $verificar_saldo = $this->saldo_conta($banco->subconta_id);

                if ($request->valor_liquidar > $verificar_saldo) {
                    return response()->json(['message' => "Pretende realizar o pagamento das encomendas utilizando os fundos da conta bancária: {$banco->conta} - {$banco->nome}. No entanto, o saldo atual não é suficiente para cobrir essa despesa. Sugerimos adicionar fundos a esta conta bancária para prosseguir com a transação."], 404);
                }

                $movimeto = Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $banco->subconta_id,
                    'status' => true,
                    'movimento' => 'S',
                    'credito' => $request->valor_liquidar,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'debito' => 0,
                    'observacao' => $request->observacao,
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                OperacaoFinanceiro::create([
                    'nome' => $dispesa->nome,
                    'status' => "pago",
                    'formas' => "B",
                    'motante' => $request->valor_liquidar,
                    'subconta_id' => $banco->subconta_id,
                    'fornecedor_id' => $fornecedor->id,
                    'model_id' => $dispesa->id,
                    'type' => "D",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'data_recebimento' => date("Y-m-d"),
                    'code' => $code,
                    'descricao' => $dispesa->nome,
                    'movimento' => "S",
                    'user_open_id' => Auth::user()->id,
                    'date_at' => $request->data_pagamento,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'code_caixa' => $caixaActivo->code_caixa ?? NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                ]);
            }

            if ($encomenda->descontado == 1) {
                $total = $request->valor_liquidar;
            } else {
                $total = $request->valor_liquidar + $encomenda->desconto_valor;
            }

            $encomenda->descontado = 1;

            ## CREDITAR FORNECEDOR
            $movimeto = Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $fornecedor->subconta_id,
                'status' => true,
                'movimento' => 'E',
                'credito' => 0,
                'debito' => $total,
                'observacao' => $request->observacao,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'code' => $code,
                'data_at' => $request->data_pagamento,
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => $this->exercicio(),
                'periodo_id' => $this->periodo(),
            ]);

            $factura->valor_divida -= $request->valor_liquidar;
            $factura->valor_pago += $request->valor_liquidar;
            $factura->total_pago += $request->valor_liquidar;

            if ($factura->total_pago >= $factura->valor_factura) {
                $status_factura = true;
                $status2 = "concluido";
            } else {
                $status_factura = false;
                $status2 = "nao concluido";
            }

            $factura->status = $status_factura;
            $factura->status2 = $status2;
            $factura->observacao = $request->observacao;
            $factura->data_pagamento = $request->data_pagamento;
            $factura->update();

            $encomenda->total_a_pagar -= $request->valor_liquidar;
            $encomenda->tota_pago += $request->valor_liquidar;

            if ($encomenda->tota_pago >= $encomenda->total_a_pagar) {
                $status_pagamento = true;
            } else {
                $status_pagamento = false;
            }

            $encomenda->status_pagamento = $status_pagamento;
            $encomenda->update();

            $total_pagamentos = FacturaEncomendaFornecedorPagamento::where('factura_id', $factura->id)->where('entidade_id', $entidade->empresa->id)->count() + 1;

            $forma_pag = TipoPagamento::where('tipo', $request->forma_pagamento_id)->first();

            FacturaEncomendaFornecedorPagamento::create([
                'forma_pagamento_id' => $forma_pag->id ?? NULL,
                'factura_id' => $factura->id,
                'data_pagamento' => $request->data_pagamento,
                'observacao' => $request->observacao,
                'valor_pago' => $request->valor_liquidar,
                'descricao' => "PN {$total_pagamentos}",
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

        return response()->json(['message' => "Factura Paga com sucesso", 'success' => true, 'redirect' => route('fornecedores-facturas-encomendas.show', $factura->id)]);

        // Alert::success('Sucesso', 'Factura Paga com sucesso');
        // return redirect()->route('fornecedores-facturas-encomendas.show');

    }

    public function duplicarFacturaCompra($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar facturas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $factura = FacturaEncomendaFornecedor::with('fornecedor', 'user', 'encomenda')->findOrFail($id);

        $encomenda = EncomendaFornecedore::with('fornecedor', 'user')->findOrFail($factura->encomenda_id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => "Duplicar factura",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "factura" => $factura,
            "encomenda" => $encomenda,
            "lojas" => $lojas,

            "fornecedores" => Fornecedore::where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])->get(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.facturas.duplicar-factura', $head);
    }

    public function duplicarFacturaCompraStore(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'factura' => 'required',
            'valor_factura' => 'required',
            'data_factura' => 'required',
            'data_vencimento' => 'required',
            'encomenda_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $factura = FacturaEncomendaFornecedor::findOrFail($request->factura_id);
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $encomenda = EncomendaFornecedore::findOrFail($request->encomenda_id);

            $totalEncomendas = FacturaEncomendaFornecedor::where('entidade_id', $entidade->empresa->id)
                ->count()  + 1;

            $totalEncomendas = "FT " . date('y') . "" . date('m') . "" . date('d') . "-" . $totalEncomendas;

            $create = FacturaEncomendaFornecedor::create([
                'factura' => $totalEncomendas,
                'fornecedor_id' => $request->fornecedor_id,
                'encomenda_id' => $factura->encomenda_id,
                'user_id' => Auth::user()->id,
                'desconto' => $encomenda->desconto,
                'desconto_valor' => $encomenda->desconto_valor,
                'valor_factura' => $request->valor_factura,
                'valor_pago' => 0,
                'valor_divida' => $request->valor_factura,
                'data_factura' => $request->data_factura,
                'data_vencimento' => $request->data_vencimento,
                'observacao' => $request->observacao,
                'referenciante' => $encomenda->factura,
                'status' => false,
                'status2' => "nao concluido",
                'status3' => "original",
                'entidade_id' => $entidade->empresa->id,
            ]);


            $conta = ContaFornecedore::where('fornecedor_id', $encomenda->fornecedor_id)->first();

            $upatedConta = ContaFornecedore::findOrFail($conta->id);
            $upatedConta->saldo += $request->valor_factura;
            $upatedConta->divida_corrente += $request->valor_factura;
            $upatedConta->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Factura Paga com sucesso", 'success' => true, 'redirect' => route('fornecedores-facturas-encomendas.show', $create->id)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir($id)
    {
        $factura = FacturaEncomendaFornecedor::with(['encomenda', 'pagamentos', 'fornecedor'])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Factura: {$factura->factura}",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa" => $empresa,
            "factura" => $factura,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.fornecedores.facturas.imprimir', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
