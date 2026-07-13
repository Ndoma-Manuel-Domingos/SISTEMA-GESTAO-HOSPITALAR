<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\ContaBancaria;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Exercicio;
use App\Models\ItemReserva;
use App\Models\ItemReservaMesa;
use App\Models\ItemVenda;
use App\Models\Mesa;
use App\Models\MotivoReserva;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Reserva;
use App\Models\ReservaMesa;
use App\Models\Periodo;
use App\Models\Produto;
use App\Models\Quarto;
use App\Models\Receita;
use App\Models\Subconta;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use phpseclib\Crypt\RSA;
use PDF;

class ReservaMesaController extends Controller
{

    use TraitHelpers;
    use TraitChavesSaft;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar reserva')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $reservas = ReservaMesa::when($request->cliente_id, function ($query, $value) {
            $query->where("cliente_id", $value);
        })
            ->when($request->status_reserva, function ($query, $value) {
                $query->where("status", $value);
            })
            ->when($request->status_pagamento, function ($query, $value) {
                $query->where("pagamento", $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("data_entrada", ">=", $value);
            })
            ->with([
                // "quarto",
                "exercicio",
                "periodo",
                "cliente.estado_civil",
                "cliente.seguradora",
                "cliente.provincia",
                "cliente.municipio",
                "cliente.distrito"
            ])
            ->where("entidade_id", $entidade->empresa->id)
            ->orderBy("created_at", "desc")
            ->get();

        $clientes = Cliente::with(["estado_civil", "seguradora", "provincia", "municipio", "distrito", "reservas"])
            ->where("entidade_id", "=", $entidade->empresa->id)
            ->get();

        $quartos = Quarto::where("entidade_id", "=", $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Reservas de Mesas",
            "descricao" => env("APP_NAME"),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "clientes" => $clientes,
            "requests" => $request->all("hora_entrada", "hora_saida", "data_inicio", "data_final", "cliente_id", "status_reserva", "status_pagamento", "quarto_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas-mesas.index', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function diario_check(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar reserva')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $reservas = Reserva::when($request->cliente_id, function ($query, $value) {
            $query->where('cliente_id', $value);
        })
            ->when($request->status_reserva, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->quarto_id, function ($query, $value) {
                $query->where('quarto_id', $value);
            })
            ->when($request->status_pagamento, function ($query, $value) {
                $query->where('pagamento', $value);
            })
            ->when($request->hora_entrada, function ($query, $value) {
                $query->where('hora_entrada', $value);
            })
            ->when($request->hora_saida, function ($query, $value) {
                $query->where('hora_saida', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_inicio', '>=', $value);
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_final', '<=', $value);
            })
            ->where('data_final', "=", date("Y-m-d"))
            ->whereIn('status', ['SUCESSO', 'EM USO'])
            ->with([
                'quarto',
                'exercicio',
                'periodo',
                'cliente.estado_civil',
                'cliente.seguradora',
                'cliente.provincia',
                'cliente.municipio',
                'cliente.distrito'
            ])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();


        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Check Out Mesas",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "clientes" => $clientes,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas-mesas.chek-out', $head);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function diario_check_in(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar reserva')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $reservas = Reserva::when($request->cliente_id, function ($query, $value) {
            $query->where('cliente_id', $value);
        })
            ->when($request->status_reserva, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->quarto_id, function ($query, $value) {
                $query->where('quarto_id', $value);
            })
            ->when($request->status_pagamento, function ($query, $value) {
                $query->where('pagamento', $value);
            })
            ->when($request->hora_entrada, function ($query, $value) {
                $query->where('hora_entrada', $value);
            })
            ->when($request->hora_saida, function ($query, $value) {
                $query->where('hora_saida', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_inicio', '>=', $value);
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_final', '<=', $value);
            })
            ->where('data_inicio', "=", date("Y-m-d"))
            ->whereIn('status', ['PENDENTE', 'EM USO'])
            ->with([
                'quarto',
                'exercicio',
                'periodo',
                'cliente.estado_civil',
                'cliente.seguradora',
                'cliente.provincia',
                'cliente.municipio',
                'cliente.distrito'
            ])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();


        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Check In Mesas",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "clientes" => $clientes,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas-mesas.chek-in', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagamento(Request $request, $id)
    {
        //
        $user = auth()->user();

        if (!$user->can('criar reserva')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $reserva = ReservaMesa::findOrFail($id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)
            ->where('status_admin', 'liberado')
            ->orderBy('id', 'asc')
            ->get();

        $receitas = Receita::where('entidade_id', '=', $entidade->empresa->id)
            ->where('type', 'R')
            ->get();

        $forma_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => "Fazer Pagamento da Reserva da Mesa",
            "descricao" => env('APP_NAME'),
            "bancos" => $bancos,
            "caixas" => $caixas,
            "receitas" => $receitas,
            "forma_pagamentos" => $forma_pagamentos,
            "clientes" => $clientes,
            "reserva" => $reserva,
            "requests" => $request->all('quarto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas-mesas.fazer-pagamento', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pagamento_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar reserva')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'reserva_id' => 'required|string',
            'actualizar_check_in' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', '=', Auth::user()->id)
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->first();


            $reserva = ReservaMesa::with(["produto", "cliente", "items"])->findOrFail($request->reserva_id);
            $receita = Receita::findOrFail($request->receita_id);
            $cliente = Cliente::findOrFail($reserva->cliente_id);
            $forma_pagamento = TipoPagamento::where('tipo', $request->forma_pagamento_id)->first();


            $tot___ = $reserva->valor_total;

            if ($request->actualizar_check_in == "sim") {

                $reserva->user_check_in = $user->id;
                $reserva->data_check_in = date("Y-m-d");
                $reserva->hora_check_in = date("h:i:s");
                $reserva->check = 'IN';
                $reserva->status = 'EM USO';

                foreach ($reserva->items as $item) {
                    $mesa = Mesa::findOrFail($item->mesa_id);
                    $mesa->solicitar_ocupacao = "OCUPADA";
                    $mesa->update();
                }

                $reserva->update();
            }


            $code = uniqid(time());
            $caixa_id = NULL;
            $banco_id = NULL;

            if ($forma_pagamento->tipo == "NU") {

                if ($request->caixa_id == "") {
                    return response()->json(['success' => true, 'message' => "Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!"], 404);
                }

                $caixa = Caixa::findOrFail($request->caixa_id);
                $subconta_id = $caixa->subconta_id;
                $caixa_id = $caixa->id;

                $valor_cash = (float) $request->valor_entregue;
                $valor_multicaixa = 0;

                OperacaoFinanceiro::create([
                    'nome' => $receita->nome,
                    'status' => "pago",
                    'formas' => "C",
                    'motante' => $request->valor_entregue,
                    'subconta_id' => $caixa->subconta_id,
                    'fornecedor_id' => $request->cliente_id,
                    'model_id' =>  $receita->id,
                    'type' => "R",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => "PAGAMENTO DA RESERVA DA MESA",
                    'movimento' => "E",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'date_at' => $request->data_pagamento,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $request->exercicio_id ?? $this->exercicio(),
                    'periodo_id' => $request->periodo_id ?? $this->periodo(),
                ]);

                // contabilidade  DEBITAR CAIXAR     
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_id,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'E',
                    'observacao' => "pagamento {$receita->nome}",
                    'credito' => 0,
                    'debito' => $tot___,
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            if ($forma_pagamento->tipo == "MB") {
                if ($request->banco_id == "") {
                    return response()->json(['success' => true, 'message' => "Deves selecionar o banco onde será retirado o valor para o pagamento da factura!"], 404);
                }

                $banco = ContaBancaria::findOrFail($request->banco_id);
                $subconta_id = $banco->subconta_id;
                $banco_id = $banco->id;

                $valor_cash = 0;
                $valor_multicaixa = (float) $request->valor_entregue;

                OperacaoFinanceiro::create([
                    'nome' => $receita->nome,
                    'status' => "pago",
                    'formas' => "B",
                    'motante' => $request->valor_entregue,
                    'subconta_id' => $banco->subconta_id,
                    'fornecedor_id' => $request->cliente_id,
                    'model_id' =>  $receita->id,
                    'type' => "R",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => "PAGAMENTO DA RESERVA DA MESA",
                    'movimento' => "E",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'date_at' => $request->data_pagamento,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $request->exercicio_id ?? $this->exercicio(),
                    'periodo_id' => $request->periodo_id ?? $this->periodo(),
                ]);

                // contabilidade  DEBITAR CAIXAR     
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $subconta_id,
                    'exercicio_id' => $this->exercicio(),
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'E',
                    'observacao' => "pagamento {$receita->nome}",
                    'credito' => 0,
                    'debito' => $tot___,
                    'code' => $code,
                    'data_at' => $request->data_pagamento,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            // CREDITAR CLIENTE
            Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $cliente->subconta_id,
                'exercicio_id' => $this->exercicio(),
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'periodo_id' => $this->periodo(),
                'status' => true,
                'movimento' => 'S',
                'observacao' => "pagamento {$receita->nome}",
                'credito' => $tot___,
                'debito' => 0,
                'code' => $code,
                'data_at' => $request->data_pagamento,
                'entidade_id' => $entidade->empresa->id,
            ]);

            // ITENS

            $mesa_id = NULL;
            $status_uso = "MESA";

            // calcudo do total de incidencia
            //________________ valor total _____________
            $valorBase = ($reserva->produto->preco_custo) * $reserva->total_dias;
            // calculo do iva
            $valorIva = ($reserva->produto->taxa / 100) * $valorBase;

            $retencao_fonte = 0;

            $valor_ = $valorBase + $valorIva;
            $retencao_fonte = 0;

            if ($reserva->produto->tipo == "S") {
                if ($reserva->produto->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
                    $retencao_fonte = $valor_ * $entidade->empresa->taxa_retencao_fonte / 100;
                }
            } else {
                $retencao_fonte = 0;
            }

            ItemVenda::create([
                "produto_id" => $reserva->produto->id,
                "movimento_id" => 1,
                "quantidade" => $reserva->total_dias,
                'quantidade_devolvida' => 0,
                "user_id" => Auth::user()->id,
                "valor_pagar" => $valorBase + $valorIva,
                "preco_unitario" => $reserva->produto->preco_venda,
                "custo" => $reserva->produto->preco_custo * $reserva->total_dias,
                "lucro" => ($reserva->produto->preco_venda * $reserva->total_dias) - ($reserva->produto->preco_custo * $reserva->total_dias),
                "desconto_aplicado" => 0,
                "retencao_fonte" => $retencao_fonte,
                "status" => "processo",
                "valor_base" => $valorBase,
                "valor_iva" => $valorIva,
                "desconto_aplicado_valor" => 0,
                "iva" => $reserva->produto->imposto,
                "iva_taxa" => $reserva->produto->taxa,
                "texto_opcional" => "",
                "status_uso" => $status_uso,
                "caixa_id" => $caixa_id,
                "banco_id" => $banco_id,
                "mesa_id" => $mesa_id,
                "code" => NULL,
                "numero_serie" => "",
                "entidade_id" => $entidade->empresa->id,
            ]);

            $movimentos = ItemVenda::where("code", NULL)
                ->where("entidade_id", $entidade->empresa->id)
                ->where("status", "processo")
                ->where("user_id", Auth::user()->id)
                ->get();

            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0;
            $totalDesconto = 0;
            $totalRetencao = 0;

            $lucro_total = 0;
            $custo_total = 0;

            if ($movimentos) {
                foreach ($movimentos as $value) {
                    $update = ItemVenda::findOrFail($value->id);
                    $update->code = $code;
                    $update->status = "realizado";
                    $update->update();

                    $lucro_total += $value->lucro;
                    $custo_total += $value->custo;
                    $totalValorBase += $value->valor_base;
                    $totalValorIva += $value->valor_iva;
                    $totalItems += $value->quantidade;
                    $totalDesconto += $value->desconto_aplicado_valor;
                    $totalRetencao += $value->retencao_fonte;
                }
            }

            // END ITENS

            if (($request->valor_entregue + $reserva->valor_pago) >= $reserva->valor_total) {
                $status = "EFECTUADO";
                $reserva->valor_divida = 0;
                $reserva->valor_pago = $request->valor_entregue;
            } else {
                $status = "NAO EFECTUADO";
                $reserva->valor_divida = $reserva->valor_pago - $request->valor_entregue;
                $reserva->valor_pago = $request->valor_entregue;
            }

            $contarFactura = Venda::where('factura', "FR")
                ->where('ano_factura', $entidade->empresa->ano_factura)
                ->where('entidade_id', $entidade->empresa->id)
                ->count();

            $numeroFactura = $contarFactura + 1;

            $codigo_designacao_factura = "FR {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";

            $ultimoRecibo = Venda::where('factura', "FR")
                ->where('ano_factura', $entidade->empresa->ano_factura)
                ->where('entidade_id', $entidade->empresa->id)
                ->orderBy('id', 'DESC')
                ->first();

            if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {
                return response()->json([
                    'message' => 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                    Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!'
                ], 400);
            }

            if (!$ultimoRecibo) {
                $hashAnterior = "";
            } else {
                $hashAnterior = $ultimoRecibo->hash;
            }

            $data_emissao = $request->data_pagamento . " " . date('H:i:s');
            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $data_emissao);

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ";{$codigo_designacao_factura};" . number_format($tot___, 2, ".", "") . ';' . $hashAnterior;

            // dd($plaintext);

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $valor_extenso = $this->valor_por_extenso($tot___);

            $statusFactura = "pago";
            $retificado = "N";
            $convertido_factura = "N";
            $factura_divida = "N";
            $anulado = "N";

            $create_factura = Venda::create([
                'codigo_factura' => $numeroFactura,
                'status' => true,
                'status_venda' => "realizado",
                'status_factura' => $statusFactura,
                'user_id' => Auth::user()->id,
                'cliente_id' => $cliente->id,
                'quarto_id' => $reserva->id,
                'valor_entregue' => $request->valor_entregue,
                'valor_total' => $tot___,
                'lucro_total' => $lucro_total,
                'custo_total' => $custo_total,
                'valor_divida' => 0,
                'total_retencao_fonte' => $totalRetencao,
                'valor_pago' => 0,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'ano_factura' => $entidade->empresa->ano_factura,
                'prazo' => 0,
                'valor_troco' => $tot___ - $tot___,
                'data_emissao' => $request->data_pagamento,
                'data_documento' => $datactual,
                'data_vencimento' => $request->data_pagamento,
                'data_disponivel' => $request->data_pagamento,
                'code' => $code,
                'desconto_percentagem' => 0,
                'desconto' => $totalDesconto,
                'pagamento' => $forma_pagamento->tipo,
                'factura' => "FR",
                'factura_next' => $codigo_designacao_factura,
                'observacao' => "prestação de serviços reserva",
                'referencia' => "prestação de serviços reserva",
                'entidade_id' => $entidade->empresa->id,

                'nome_cliente' => $cliente->nome,
                'documento_nif' => $cliente->nif,

                'retificado' => $retificado,
                'convertido_factura' => $convertido_factura,
                'factura_divida' => $factura_divida,
                'anulado' => $anulado,

                'moeda' => $entidade->empresa->moeda ?? 'AKZ',
                'valor_extenso' => $valor_extenso,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,
                'texto_hash' => $plaintext,
                'hash' => base64_encode($signaturePlaintext),
                'nif_cliente' => $cliente->nif,

                'total_iva' => $totalValorIva,
                'total_incidencia' => $totalValorBase,
                'quantidade' => $totalItems,
            ]);

            $movimentos = ItemVenda::where('code', $code)->get();

            if ($movimentos) {
                foreach ($movimentos as $item) {
                    $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();
                    ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_prestacao_servico->id,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $tot___,
                        'debito' => 0,
                        'observacao' => "pagamento de reserva",
                        'code' => $code,
                        'data_at' => $request->data_pagamento,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    $update = ItemVenda::findOrFail($item->id);
                    $update->factura_id = $create_factura->id;
                    $update->update();
                }
            }

            // FINALIZAR RESERVA

            $reserva->valor_total = $request->valor_entregue;
            $reserva->observacao = $request->observacao;
            $reserva->pagamento = $status;
            $reserva->subconta_id = $subconta_id;
            $reserva->forma_pagamento_id = $forma_pagamento->id;

            $reserva->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json(['success' => true, 'factura' => $create_factura, 'message' => "Dados Salvos com sucesso!"], 200);
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

        if (!$user->can('criar reserva')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $periodos = Periodo::where('exercicio_id', $this->exercicio())->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $mesas = Mesa::where("solicitar_ocupacao", "LIVRE")->where('entidade_id', $entidade->empresa->id)
            ->get();

        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)
            ->where('status_admin', 'liberado')
            ->orderBy('id', 'asc')
            ->get();

        $produtos = Produto::where("tipo", "S")->where('entidade_id', $entidade->empresa->id)->get();

        $receitas = Receita::where('entidade_id', '=', $entidade->empresa->id)
            ->where('type', 'R')
            ->get();

        $forma_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => "Fazer nova reserva",
            "descricao" => env('APP_NAME'),
            "exercicios" => $exercicios,
            "bancos" => $bancos,
            "caixas" => $caixas,
            "receitas" => $receitas,
            "mesas" => $mesas,
            "periodos" => $periodos,
            "produtos" => $produtos,
            "forma_pagamentos" => $forma_pagamentos,
            "clientes" => $clientes,
            "requests" => $request->all('quarto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas-mesas.create', $head);
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

        if (!$user->can('criar reserva')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'cliente_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $forma_pagamento = TipoPagamento::where('tipo', $request->forma_pagamento_id)->first();
            $create_factura = NULL;

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', '=', Auth::user()->id)
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->first();

            $dataAtual = Carbon::now()->format('Y-m-d');
            $divida = 0;
            $valor_pago = 0;
            $valor_troco = 0;
            $subconta_id = NULL;
            $valor_a_pagar = 0;

            $data_emissao = date("Y-m-d");

            $code = uniqid(time());

            if ($request->marcar_como == "sim") {

                $caixa_id = NULL;
                $banco_id = NULL;
                $tot___ = $request->total_factura;


                $receita = Receita::findOrFail($request->receita_id);
                $cliente = Cliente::findOrFail($request->cliente_id);

                if ($request->forma_pagamento_id == "") {
                    return response()->json(['success' => true, 'message' => "Deves selecionar uma forma de pagamento da factura!"], 404);
                }

                $receita = Receita::findOrFail($request->receita_id);

                if ($request->valor_entregue >= $request->total_factura) {
                    $divida = 0;
                    $valor_a_pagar = $request->total_factura;
                } else {
                    $divida = $request->total_factura - $request->valor_entregue;
                    $valor_a_pagar = $request->valor_entregue;
                }

                $valor_pago = $request->valor_entregue - $request->total_factura;
                $valor_troco = $request->valor_entregue - $request->total_factura;

                if ($forma_pagamento->tipo == "NU") {
                    if ($request->caixa_id == "") {
                        return response()->json(['success' => true, 'message' => "Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!"], 404);
                    }

                    $caixa = Caixa::findOrFail($request->caixa_id);
                    $subconta_id = $caixa->subconta_id;
                    $caixa_id = $caixa->id;


                    $valor_cash = (float) $request->valor_entregue;
                    $valor_multicaixa = 0;

                    OperacaoFinanceiro::create([
                        'nome' => $receita->nome,
                        'status' => "pago",
                        'formas' => "C",
                        'motante' => $valor_a_pagar,
                        'subconta_id' => $caixa->subconta_id,
                        'cliente_id' => $request->cliente_id,
                        'model_id' =>  $receita->id,
                        'type' => "R",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'descricao' => "PAGAMENTO DA RESERVA DA MESA",
                        'movimento' => "E",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'date_at' => $data_emissao,
                        'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'user_id' => Auth::user()->id,
                        'user_open_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id ?? $this->exercicio(),
                        'periodo_id' => $request->periodo_id ?? $this->periodo(),
                    ]);

                    // contabilidade  DEBITAR CAIXAR     
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_id,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'E',
                        'observacao' => "pagamento {$receita->nome}",
                        'credito' => 0,
                        'debito' => $valor_a_pagar,
                        'code' => $code,
                        'data_at' => $data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }

                if ($forma_pagamento->tipo  == "MB") {
                    if ($request->banco_id == "") {
                        return response()->json(['success' => true, 'message' => "Deves selecionar o banco onde será retirado o valor para o pagamento da factura!"], 404);
                    }

                    $banco = ContaBancaria::findOrFail($request->banco_id);
                    $subconta_id = $banco->subconta_id;
                    $banco_id = $banco->id;


                    $valor_cash = 0;
                    $valor_multicaixa = (float) $request->valor_entregue;

                    OperacaoFinanceiro::create([
                        'nome' => $receita->nome,
                        'status' => "pago",
                        'formas' => "B",
                        'motante' => $valor_a_pagar,
                        'subconta_id' => $banco->subconta_id,
                        'cliente_id' => $request->cliente_id,
                        'model_id' =>  $receita->id,
                        'type' => "R",
                        'parcelado' => "N",
                        'status_pagamento' => "pago",
                        'code' => $code,
                        'descricao' => "PAGAMENTO DA RESERVA DA MESA",
                        'movimento' => "E",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'date_at' => $data_emissao,
                        'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'user_id' => Auth::user()->id,
                        'user_open_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $request->exercicio_id ?? $this->exercicio(),
                        'periodo_id' => $request->periodo_id ?? $this->periodo(),
                    ]);

                    // contabilidade  DEBITAR CAIXAR     
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'movimento' => 'E',
                        'observacao' => "pagamento {$receita->nome}",
                        'credito' => 0,
                        'debito' => $valor_a_pagar,
                        'code' => $code,
                        'data_at' => $request->data_emissao,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }

                // CREDITAR CLIENTE
                Movimento::create([
                    'user_id' => Auth::user()->id,
                    'subconta_id' => $cliente->subconta_id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'status' => true,
                    'movimento' => 'S',
                    'observacao' => "pagamento {$receita->nome}",
                    'credito' => $valor_a_pagar,
                    'debito' => 0,
                    'code' => $code,
                    'data_at' => $data_emissao,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                // ITENS

                $produto = Produto::findOrFail($request->produto_id);

                $mesa_id = NULL;
                $status_uso = "MESA";

                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = ($produto->preco_custo) * $request->total_mesas;
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;

                $retencao_fonte = 0;

                $valor_ = $valorBase + $valorIva;
                $retencao_fonte = 0;

                if ($produto->tipo == "S") {
                    if ($produto->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
                        $retencao_fonte = $valor_ * $entidade->empresa->taxa_retencao_fonte / 100;
                    }
                } else {
                    $retencao_fonte = 0;
                }


                ItemVenda::create([
                    "produto_id" => $produto->id,
                    "movimento_id" => 1,
                    "quantidade" => $request->total_mesas,
                    'quantidade_devolvida' => 0,
                    "user_id" => Auth::user()->id,
                    "valor_pagar" => $valorBase + $valorIva,
                    "preco_unitario" => $produto->preco_venda,
                    "custo" => $produto->preco_custo * $request->total_mesas,
                    "lucro" => ($produto->preco_venda * $request->total_mesas) - ($produto->preco_custo * $request->total_mesas),
                    "desconto_aplicado" => 0,
                    "retencao_fonte" => $retencao_fonte,
                    "status" => "processo",
                    "valor_base" => $valorBase,
                    "valor_iva" => $valorIva,
                    "desconto_aplicado_valor" => 0,
                    "iva" => $produto->imposto,
                    "iva_taxa" => $produto->taxa,
                    "texto_opcional" => "",
                    "status_uso" => $status_uso,
                    "caixa_id" => $caixa_id,
                    "banco_id" => $banco_id,
                    "mesa_id" => $mesa_id,
                    "code" => NULL,
                    "numero_serie" => "",
                    "entidade_id" => $entidade->empresa->id,
                ]);

                $movimentos = ItemVenda::where("code", NULL)
                    ->where("entidade_id", $entidade->empresa->id)
                    ->where("status", "processo")
                    ->where("user_id", Auth::user()->id)
                    ->get();

                $totalValorBase = 0;
                $totalValorIva = 0;
                $totalItems = 0;
                $totalDesconto = 0;
                $totalRetencao = 0;

                $lucro_total = 0;
                $custo_total = 0;

                if ($movimentos) {
                    foreach ($movimentos as $value) {
                        $update = ItemVenda::findOrFail($value->id);
                        $update->code = $code;
                        $update->status = "realizado";
                        $update->update();

                        $lucro_total += $value->lucro;
                        $custo_total += $value->custo;
                        $totalValorBase += $value->valor_base;
                        $totalValorIva += $value->valor_iva;
                        $totalItems += $value->quantidade;
                        $totalDesconto += $value->desconto_aplicado_valor;
                        $totalRetencao += $value->retencao_fonte;
                    }
                }

                // END ITENS

                $contarFactura = Venda::where('factura', "FR")
                    ->where('ano_factura', $entidade->empresa->ano_factura)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->count();

                $numeroFactura = $contarFactura + 1;

                $codigo_designacao_factura = "FR {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";

                $ultimoRecibo = Venda::where('factura', "FR")
                    ->where('ano_factura', $entidade->empresa->ano_factura)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->orderBy('id', 'DESC')
                    ->first();

                if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {
                    return response()->json([
                        'message' => 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
                    Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!'
                    ], 400);
                }

                if (!$ultimoRecibo) {
                    $hashAnterior = "";
                } else {
                    $hashAnterior = $ultimoRecibo->hash;
                }

                $data_emissao = $data_emissao . " " . date('H:i:s');
                //Manipulação de datas: data actual
                $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $data_emissao);

                $rsa = new RSA(); //Algoritimo RSA

                $privatekey = $this->pegarChavePrivada();
                $publickey = $this->pegarChavePublica();

                // Lendo a private key
                $rsa->loadKey($privatekey);

                /**
                 * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
                 * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

                $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ";{$codigo_designacao_factura};" . number_format($tot___, 2, ".", "") . ';' . $hashAnterior;

                // dd($plaintext);

                // HASH
                $hash = 'sha1'; // Tipo de Hash
                $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

                //ASSINATURA
                $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
                $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

                // Lendo a public key
                $rsa->loadKey($publickey);

                $valor_extenso = $this->valor_por_extenso($tot___);

                $statusFactura = "pago";
                $retificado = "N";
                $convertido_factura = "N";
                $factura_divida = "N";
                $anulado = "N";

                $create_factura = Venda::create([
                    'codigo_factura' => $numeroFactura,
                    'status' => true,
                    'status_venda' => "realizado",
                    'status_factura' => $statusFactura,
                    'user_id' => Auth::user()->id,
                    'cliente_id' => $cliente->id,
                    'quarto_id' => NULL,
                    'valor_entregue' => $request->valor_entregue,
                    'valor_total' => $tot___,
                    'lucro_total'   => $lucro_total,
                    'custo_total' => $custo_total,
                    'valor_divida' => 0,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'total_retencao_fonte' => $totalRetencao,
                    'valor_pago' => 0,
                    'ano_factura' => $entidade->empresa->ano_factura,
                    'prazo' => 0,
                    'valor_troco' => $tot___ - $tot___,
                    'data_emissao' => $data_emissao,
                    'data_documento' => $datactual,
                    'data_vencimento' => $data_emissao,
                    'data_disponivel' => $data_emissao,
                    'code' => $code,
                    'desconto_percentagem' => 0,
                    'desconto' => $totalDesconto,
                    'pagamento' => $forma_pagamento->tipo,
                    'factura' => "FR",
                    'factura_next' => $codigo_designacao_factura,
                    'observacao' => "pagamento de reserva de mesa",
                    'referencia' => "pagamento de reserva de mesa",
                    'entidade_id' => $entidade->empresa->id,

                    'nome_cliente' => $cliente->nome,
                    'documento_nif' => $cliente->nif,

                    'retificado' => $retificado,
                    'convertido_factura' => $convertido_factura,
                    'factura_divida' => $factura_divida,
                    'anulado' => $anulado,

                    'moeda' => $entidade->empresa->moeda ?? 'AKZ',
                    'valor_extenso' => $valor_extenso,
                    'valor_cash' => $valor_cash,
                    'valor_multicaixa' => $valor_multicaixa,
                    'texto_hash' => $plaintext,
                    'hash' => base64_encode($signaturePlaintext),
                    'nif_cliente' => $cliente->nif,

                    'total_iva' => $totalValorIva,
                    'total_incidencia' => $totalValorBase,
                    'quantidade' => $totalItems,
                ]);

                $movimentos = ItemVenda::where('code', $code)->get();

                if ($movimentos) {
                    foreach ($movimentos as $item) {
                        $subconta_prestacao_servico = Subconta::where('numero', ENV('PRESTACAO_SERVICO'))->first();
                        ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                        Movimento::create([
                            'user_id' => Auth::user()->id,
                            'subconta_id' => $subconta_prestacao_servico->id,
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $tot___,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'debito' => 0,
                            'observacao' => "prestação de serviços hospitalares",
                            'code' => $code,
                            'data_at' => $data_emissao,
                            'entidade_id' => $entidade->empresa->id,
                            'exercicio_id' => $this->exercicio(),
                            'periodo_id' => $this->periodo(),
                        ]);

                        $update = ItemVenda::findOrFail($item->id);
                        $update->factura_id = $create_factura->id;
                        $update->update();
                    }
                }

                $pagamento = 'EFECTUADO';
            } else {
                $divida = $request->total_factura;
                $pagamento = 'NAO EFECTUADO';
                $valor_pago = 0;
                $valor_troco = 0;

                $subconta_id = NULL;
            }

            $reserva = ReservaMesa::create([
                'valor_unitario' => $request->preco_unitario,
                'valor_total' => $request->total_factura,
                'valor_pago' => $valor_pago,
                'valor_divida' =>  $divida,
                'valor_troco' => $valor_troco,
                'valor_retencao_fonte' => $request->total_factura * (($entidade->empresa->taxa_retencao_fonte ?? 0) / 100),
                'total_pessoas' => $request->total_pessoas,
                'subconta_id' => $subconta_id,
                'forma_pagamento_id' => $forma_pagamento ? $forma_pagamento->id : NULL,

                'hora_entrada' => $request->hora_entrada,
                'data_entrada' => $request->data_entrada,

                'criancas' => $request->criancas,
                'numero_criancas' => $request->numero_criancas,
                'observacao' => $request->observacao,
                'produto_id' => $request->produto_id,

                'total_mesas' => $request->total_mesas,
                'code' => $code,
                'cliente_id' => $request->cliente_id,
                'status' => "PENDENTE",
                // 'quarto_id' => $request->quarto_id,
                'exercicio_id' => $request->exercicio_id,
                'periodo_id' => $request->periodo_id,
                'pagamento' => $pagamento,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            foreach ($request->mesa_id as $item) {
                if ($item) {
                    ItemReservaMesa::create([
                        "reserva_id" => $reserva->id,
                        "mesa_id" => $item,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    $mesa = Mesa::findOrFail($item);
                    $mesa->solicitar_ocupacao = "RESERVADA";
                    $mesa->update();
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

        return response()->json([
            'success' => true,
            'message' => "Dados Salvos com sucesso!",
            'reserva' => $reserva,
            'pdf_url_factura' => $create_factura ? route('factura-recibo', $create_factura->code) : NULL,
            'pdf_url' => route('imprimir-ficha-reservas-mesa', $reserva->id)
        ], 200);
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

        if (!$user->can('listar todos') && !$user->can('listar reserva')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $reserva = ReservaMesa::with([
            "subconta",
            "exercicio",
            "items",
            "periodo",
            "produto",
            "user_in_ckeck",
            "user_out_ckeck",
            "cliente.estado_civil",
            "cliente.seguradora",
            "cliente.provincia",
            "cliente.municipio",
            "cliente.distrito"
        ])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "reserva" => $reserva,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas-mesas.show', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anulacao($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar quarto')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $reserva = ReservaMesa::with(["items"])->findOrFail($id);

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('user_open_id', '=', Auth::user()->id)
                ->where('status_admin', 'liberado')
                ->where('entidade_id', '=', $entidade->empresa->id)
                ->first();

            $code = uniqid(time());

            $dispesa = Receita::wheret('type', 'D')->where('nome', 'Reembolso')->where('entidade_id', $entidade->empresa->id)->first();

            if ($reserva->pagamento == "EFECTUADO") {

                $forma = TipoPagamento::findOrFail($reserva->forma_pagamento_id);

                if ($forma->tipo == "NU") {
                    $f = "C";
                } else {
                    $f = "B";
                }

                OperacaoFinanceiro::create([
                    'nome' => $dispesa->nome,
                    'status' => "pago",
                    'formas' => $f,
                    'motante' => $reserva->valor_total,
                    'subconta_id' => $reserva->subconta_id,
                    'fornecedor_id' => $reserva->cliente_id,
                    'model_id' => $dispesa->id,
                    'type' => "D",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'descricao' => "REEMBOLSO DOS VALORES DA RESERVADA",
                    'movimento' => "S",
                    'date_at' => date("Y-m-d"),
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $request->exercicio_id ?? $this->exercicio(),
                    'periodo_id' => $request->periodo_id ?? $this->periodo(),
                ]);
            }

            $reserva->status = 'CANCELADO';
            foreach ($reserva->items as $items) {
                $mesa = Mesa::findOrFail($items->mesa_id);
                $mesa->solicitar_ocupacao = "LIVRE";
            }
            $mesa->update();
            $reserva->update();

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_in($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar quarto')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $reserva = ReservaMesa::with(["items"])->findOrFail($id);

            // if ($reserva->data_entrada != date("Y-m-d")) {
            //     return response()->json(['success' => true, 'message' => "Por favor, verifique a data de início da hospedagem do cliente registrada na reserva. A data de entrada informada na reserva não corresponde a hoje, mas sim a: {$reserva->data_inicio}!"], 404);
            // }

            $reserva->user_check_in = $user->id;
            $reserva->data_check_in = date("Y-m-d");
            $reserva->hora_check_in = date("h:i:s");
            $reserva->check = 'IN';
            $reserva->status = 'EM USO';

            foreach ($reserva->items as $item) {
                $mesa = Mesa::findOrFail($item->mesa_id);
                $mesa->solicitar_ocupacao = "OCUPADA";
                $mesa->update();
            }

            $reserva->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Check In realizado com sucesso"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_out($id)
    {
        $user = auth()->user();

        if (!$user->can('criar reserva')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $reserva = ReservaMesa::with(["items"])->findOrFail($id);

            $reserva->user_check_out = $user->id;
            $reserva->data_check_out = date("Y-m-d");
            $reserva->hora_check_out = date("h:i:s");
            $reserva->check = 'OUT';
            $reserva->status = 'SUCESSO';

            foreach ($reserva->items as $item) {
                $mesa = Mesa::findOrFail($item->mesa_id);
                $mesa->solicitar_ocupacao = "LIVRE";
                $mesa->update();
            }

            $reserva->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Check Out realizado com sucesso!!"], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar reserva')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $reserva = ReservaMesa::findOrFail($id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $periodos = Periodo::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $mesas = Mesa::where('entidade_id', '=', $entidade->empresa->id)
            ->whereIn('solicitar_ocupacao', ['LIVRE', 'RESERVADA'])
            ->get();

        $produtos = Produto::where("tipo", "S")->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();


        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "reserva" => $reserva,
            "exercicios" => $exercicios,
            "mesas" => $mesas,
            "produtos" => $produtos,
            "periodos" => $periodos,
            "clientes" => $clientes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas-mesas.edit', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar reserva')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'cliente_id' => 'required|string',
            'produto_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $reserva = ReservaMesa::findOrFail($id);
            $code = uniqid(time());

            $reserva->valor_divida = $request->total_factura;
            $reserva->valor_pago = 0;
            $reserva->valor_troco = 0;
            $reserva->valor_total = $request->total_factura;
            $reserva->valor_unitario = $request->preco_unitario;
            $reserva->valor_retencao_fonte = $request->total_factura * (6.5 / 100);
            $reserva->produto = $request->produto;
            $reserva->data_entrada = $request->data_entrada;
            $reserva->hora_entrada = $request->hora_entrada;

            $reserva->criancas = $request->criancas;
            $reserva->numero_criancas = $request->numero_criancas;
            $reserva->observacao = $request->observacao;

            $reserva->total_mesas = $request->total_mesas;
            $reserva->code = $code;
            $reserva->cliente_id = $request->cliente_id;
            $reserva->produto_id = $request->produto_id;
            $reserva->exercicio_id = $request->exercicio_id;
            $reserva->periodo_id = $request->periodo_id;
            $reserva->pagamento = "NAO EFECTUADO";
            $reserva->user_id = Auth::user()->id;
            $reserva->entidade_id = $entidade->empresa->id;
            $reserva->update();

            foreach ($reserva->items as $item) {
                $mesa = Mesa::findOrFail($item->mesa_id);
                if ($reserva->status == "PENDENTE") {
                    $mesa->solicitar_ocupacao = "LIVRE";
                } else {
                    $mesa->solicitar_ocupacao = "RESERVADA";
                }
                $mesa->update();
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar reserva')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $reserva = ReservaMesa::findOrFail($id);
            $reserva->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados excluído com sucesso!"], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function imprimir_ficha($id)
    {
        $reserva = ReservaMesa::with([
            "subconta",
            "exercicio",
            "items.mesa",
            "periodo",
            "user_in_ckeck",
            "user_out_ckeck",
            "cliente.estado_civil",
            "cliente.seguradora",
            "cliente.provincia",
            "cliente.municipio",
            "cliente.distrito"
        ])->findOrFail($id);


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Ficha da Reserva",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "reserva" => $reserva,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.reservas-mesas.ficha-reserva', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
