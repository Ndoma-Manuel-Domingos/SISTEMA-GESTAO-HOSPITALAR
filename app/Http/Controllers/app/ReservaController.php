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
use App\Models\ItemVenda;
use App\Models\MotivoReserva;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Reserva;
use App\Models\Periodo;
use App\Models\Produto;
use App\Models\Quarto;
use App\Models\Receita;
use App\Models\Subconta;
use App\Models\TipoPagamento;
use App\Models\TipoReserva;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

use phpseclib\Crypt\RSA;
use PDF;

class ReservaController extends Controller
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

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $request->status_reserva ?? "PENDENTE";

        $reservas = Reserva::whereHas('items', function ($query) use ($request) {
            $query->when($request->quarto_id, function ($query, $value) {
                $query->where('quarto_id', $value);
            });
        })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->tipo_reserva_id, function ($query, $value) {
                $query->where('tipo_reserva_id', $value);
            })
            ->when($request->status_reserva, function ($query, $value) {
                $query->where('status', $value);
            })
            // ->whereNotIn('status', ['SUCESSO'])
            ->when($request->status_pagamento, function ($query, $value) {
                $query->where('pagamento', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_inicio', $value);
            })
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

        $quartos = Quarto::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_reservas = TipoReserva::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Reservas",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "tipos_reservas" => $tipos_reservas,
            "clientes" => $clientes,
            "requests" => $request->all('data_inicio', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id', 'tipo_reserva_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.index', $head);
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

        $reservas = Reserva::whereHas('items', function ($query) use ($request) {
            $query->when($request->quarto_id, function ($query, $value) {
                $query->where('quarto_id', $value);
            });
        })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->tipo_reserva_id, function ($query, $value) {
                $query->where('tipo_reserva_id', $value);
            })
            ->when($request->status_reserva, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->status_pagamento, function ($query, $value) {
                $query->where('pagamento', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_inicio', $value);
            })
            ->where('data_final', "=", date("Y-m-d"))
            ->whereIn('status', ['SUCESSO', 'EM USO'])
            ->with([
                'items',
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
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $quartos = Quarto::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_reservas = TipoReserva::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Check Out Diários",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "clientes" => $clientes,
            "tipos_reservas" => $tipos_reservas,
            "requests" => $request->all('tipo_reserva_id', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.chek-out', $head);
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

        $reservas = Reserva::whereHas('items', function ($query) use ($request) {
            $query->when($request->quarto_id, function ($query, $value) {
                $query->where('quarto_id', $value);
            });
        })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where('cliente_id', $value);
            })
            ->when($request->tipo_reserva_id, function ($query, $value) {
                $query->where('tipo_reserva_id', $value);
            })
            ->when($request->status_reserva, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->status_pagamento, function ($query, $value) {
                $query->where('pagamento', $value);
            })
            ->whereDate('data_inicio', date("Y-m-d"))
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

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])->where('entidade_id', $entidade->empresa->id)->get();
        $quartos = Quarto::where('entidade_id', $entidade->empresa->id)->get();
        $tipos_reservas = TipoReserva::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Check In Diários",
            "descricao" => env('APP_NAME'),
            "quartos" => $quartos,
            "reservas" => $reservas,
            "tipos_reservas" => $tipos_reservas,
            "clientes" => $clientes,
            "requests" => $request->all('tipo_reserva_id', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.chek-in', $head);
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
        $reserva = Reserva::findOrFail($id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $tarefarios = Produto::where("aplicado", "Y")->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
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

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)->get();

        $forma_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => "Fazer Pagamento da Reservação",
            "descricao" => env('APP_NAME'),
            "exercicios" => $exercicios,
            "bancos" => $bancos,
            "caixas" => $caixas,
            "receitas" => $receitas,
            "quartos" => $quartos,
            "tarefarios" => $tarefarios,
            "forma_pagamentos" => $forma_pagamentos,
            "clientes" => $clientes,
            "reserva" => $reserva,
            "requests" => $request->all('quarto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.fazer-pagamento', $head);
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
            'receita_id' => 'required|string',
            'forma_pagamento_id' => 'required|string',
            'actualizar_check_in' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            $reserva = Reserva::with(["cliente", "items"])->findOrFail($request->reserva_id);
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

                foreach ($reserva->items as $res_i) {
                    $quarto = Quarto::findOrFail($res_i->quarto_id);
                    $quarto->solicitar_ocupacao = "OCUPADA";
                    $quarto->update();
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
                    'descricao' => "PAGAMENTO DA RESERVA DO QUARTO",
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
                    'periodo_id' => $this->periodo(),
                    'status' => true,
                    'movimento' => 'E',
                    'observacao' => "pagamento {$receita->nome}",
                    'credito' => 0,
                    'debito' => $tot___,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code' => $code,
                    'descricao' => "PAGAMENTO DA RESERVA DO QUARTO",
                    'movimento' => "E",
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
                'periodo_id' => $this->periodo(),
                'status' => true,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
            $status_uso = "QUARTO";

            foreach ($reserva->items as $item) {

                $tarefario = Produto::findOrFail($item->tarefario_id);

                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = ($tarefario->preco_custo) * $reserva->total_dias;
                // calculo do iva
                $valorIva = ($tarefario->taxa / 100) * $valorBase;

                $retencao_fonte = 0;

                $valor_ = $valorBase + $valorIva;
                $retencao_fonte = $valor_ * $entidade->empresa->taxa_retencao_fonte / 100;

                ItemVenda::create([
                    "produto_id" => $tarefario->id,
                    "movimento_id" => 1,
                    "quantidade" => $reserva->total_dias,
                    'quantidade_devolvida' => 0,
                    "user_id" => Auth::user()->id,
                    "valor_pagar" => $valorBase + $valorIva,
                    "preco_unitario" => $tarefario->preco_venda,
                    "custo" => $tarefario->preco_custo * $reserva->total_dias,
                    "lucro" => ($tarefario->preco_venda * $reserva->total_dias) - ($tarefario->preco_custo * $reserva->total_dias),
                    "desconto_aplicado" => 0,
                    "retencao_fonte" => $retencao_fonte,
                    "status" => "processo",
                    "valor_base" => $valorBase,
                    "valor_iva" => $valorIva,
                    "desconto_aplicado_valor" => 0,
                    "iva" => $tarefario->imposto,
                    "iva_taxa" => $tarefario->taxa,
                    "texto_opcional" => "",
                    "status_uso" => $status_uso,
                    "caixa_id" => $caixa_id,
                    "banco_id" => $banco_id,
                    "mesa_id" => $mesa_id,
                    "code" => NULL,
                    "numero_serie" => "",
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

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
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'valor_entregue' => $request->valor_entregue,
                'lucro_total' => $lucro_total,
                'custo_total' => $custo_total,
                'valor_total' => $tot___,
                'valor_divida' => 0,
                'total_retencao_fonte' => $totalRetencao,
                'valor_pago' => 0,
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
                        'status' => true,
                        'movimento' => 'S',
                        'credito' => $tot___,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'debito' => 0,
                        'observacao' => "prestação de serviços hospitalares",
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
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json(['success' => true, 'factura' => $create_factura, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    private function gerar_factura($requset) {}

    public function verificarDisponibilidadeQuarto(Request $request)
    {
        $quarto = Quarto::find($request->quarto_id);

        if (!$quarto) {
            return response()->json([
                'disponivel' => false,
                'message' => 'Quarto não encontrado.'
            ], 404);
        }

        $dataEntrada = $request->data_entrada_quarto;
        $dataSaida   = $request->data_saida_quarto;

        $horaEntrada = $request->hora_entrada;
        $horaSaida   = $request->hora_saida;

        $conflito = Quarto::where('id', $request->quarto_id)
            ->whereIn('solicitar_ocupacao', [
                'RESERVADA',
                'OCUPADA',
            ])
            ->where(function ($q) use ($dataEntrada, $dataSaida) {
                alert($dataEntrada, $dataSaida);
                $q->where('data_inicio', '<=', $dataSaida)
                    ->where('data_final', '>=', $dataEntrada);
            })
            // ->when(
            //     $dataEntrada == $dataSaida,
            //     function ($query) use ($horaEntrada, $horaSaida) {
            //         $query->where(function ($q) use ($horaEntrada, $horaSaida) {
            //             $q->where('hora_entrada', '<', $horaSaida)
            //                 ->where('hora_saida', '>', $horaEntrada);
            //         });
            //     }
            // )
            ->exists();

        dd($conflito);

        // =========================================
        // SEM CONFLITO
        // =========================================

        if (!$conflito) {

            return response()->json([
                'disponivel' => true,
                'message' => 'Quarto disponível.'
            ]);
        }

        // =========================================
        // GERAR HORÁRIOS DISPONÍVEIS
        // =========================================

        $horariosDisponiveis = [];

        $inicioDia = Carbon::createFromTime(0, 0, 0);
        $fimDia    = Carbon::createFromTime(23, 59, 59);

        $horaReservadaInicio = Carbon::parse($quarto->hora_entrada);
        $horaReservadaFim    = Carbon::parse($quarto->hora_saida);

        // Horário antes da reserva
        if ($inicioDia < $horaReservadaInicio) {

            $horariosDisponiveis[] = [
                'inicio' => $inicioDia->format('H:i'),
                'fim'    => $horaReservadaInicio->format('H:i'),
            ];
        }

        // Horário depois da reserva
        if ($horaReservadaFim < $fimDia) {

            $horariosDisponiveis[] = [
                'inicio' => $horaReservadaFim->format('H:i'),
                'fim'    => $fimDia->format('H:i'),
            ];
        }

        return response()->json([
            'disponivel' => false,
            'message' => 'Quarto indisponível neste horário.',
            'quarto' => [
                'numero' => $quarto->numero,
                'status' => $quarto->status,
                'data_inicio' => $quarto->data_inicio,
                'data_final' => $quarto->data_final,
                'hora_entrada' => $quarto->hora_entrada,
                'hora_saida' => $quarto->hora_saida,
            ],
            'horarios_disponiveis' => $horariosDisponiveis
        ]);
    }

    /*public function verificarDisponibilidadeQuarto(Request $request)
    {
        $existe = Quarto::where('id', $request->quarto_id)
            ->whereHas('reserva', function ($query) use ($request) {
                $query->where('data_inicio', '>=', $request->data_entrada_quarto)
                    ->whereDate('data_final', '<=', $request->data_saida_quarto);
            })
            ->exists();

        if ($existe) {
            // Simulando horários disponíveis para exibir
            $horariosDisponiveis = [
                ['08:00', '10:00'],
                ['11:00', '13:00'],
                ['14:00', '16:00'],
            ];

            return response()->json([
                'disponivel' => false,
                'horarios' => $horariosDisponiveis,
            ]);
        }

        return response()->json([
            'disponivel' => true,
        ]);
    }*/

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

        $tarefarios = Produto::where("aplicado", "Y")->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $bancos = ContaBancaria::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $caixas = Caixa::where('entidade_id', '=', $entidade->empresa->id)
            ->where('status_admin', 'liberado')
            ->orderBy('id', 'asc')
            ->get();

        $motivos = MotivoReserva::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->whereIn('solicitar_ocupacao', ['LIVRE', 'RESERVADA'])
            ->get();

        $tipo_reservas = TipoReserva::where('entidade_id', $entidade->empresa->id)
            ->get();

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
            "quartos" => $quartos,
            "tipo_reservas" => $tipo_reservas,
            "receitas" => $receitas,
            "tarefarios" => $tarefarios,
            "periodos" => $periodos,
            "motivos" => $motivos,
            "forma_pagamentos" => $forma_pagamentos,
            "clientes" => $clientes,
            "requests" => $request->all('quarto_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.create', $head);
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
            'quartos' => 'array|required'
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
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            $dataAtual = Carbon::now()->format('Y-m-d');
            $divida = 0;
            $valor_pago = 0;
            $valor_troco = 0;
            $subconta_id = NULL;
            $valor_a_pagar = 0;

            $total = $request->valor_total_geral ?? 0;

            $code = uniqid(time());

            if ($request->marcar_como == "sim") {

                $caixa_id = NULL;
                $banco_id = NULL;
                $tot___ = $total;

                $receita = Receita::findOrFail($request->receita_id);
                $cliente = Cliente::findOrFail($request->cliente_id);

                if ($request->forma_pagamento_id == "") {
                    return response()->json(['success' => true, 'message' => "Deves selecionar uma forma de pagamento da factura!"], 404);
                }

                $receita = Receita::findOrFail($request->receita_id);

                if ($request->valor_entregue >= $total) {
                    $divida = 0;
                    $valor_a_pagar = $total;
                } else {
                    $divida = $total - $request->valor_entregue;
                    $valor_a_pagar = $request->valor_entregue;
                }

                $valor_pago = $request->valor_entregue - $total;
                $valor_troco = $request->valor_entregue - $total;

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
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'descricao' => "PAGAMENTO DA RESERVA DO QUARTO",
                        'movimento' => "E",
                        'date_at' => $request->data_emissao,
                        'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'user_id' => Auth::user()->id,
                        'user_open_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    // contabilidade  DEBITAR CAIXAR     
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        'code' => $code,
                        'descricao' => "PAGAMENTO DA RESERVA DO QUARTO",
                        'movimento' => "E",
                        'date_at' => $request->data_emissao,
                        'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                        'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                        'user_id' => Auth::user()->id,
                        'user_open_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    // contabilidade  DEBITAR CAIXAR     
                    Movimento::create([
                        'user_id' => Auth::user()->id,
                        'subconta_id' => $subconta_id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                        'status' => true,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
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
                    'status' => true,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'movimento' => 'S',
                    'observacao' => "pagamento {$receita->nome}",
                    'credito' => $valor_a_pagar,
                    'debito' => 0,
                    'code' => $code,
                    'data_at' => $request->data_emissao,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                // ITENS
                foreach ($request->quartos as $item) {

                    $tarefario = Produto::findOrFail($item['tarifario_id']);

                    $mesa_id = NULL;
                    $status_uso = "QUARTO";

                    // calcudo do total de incidencia
                    //________________ valor total _____________
                    $valorBase = ($tarefario->preco_custo) * ($request->total_dias_reservado ?? 1);
                    // calculo do iva
                    $valorIva = ($tarefario->taxa / 100) * $valorBase;

                    $retencao_fonte = 0;

                    $valor_ = $valorBase + $valorIva;
                    $retencao_fonte = 0;

                    if ($tarefario->tipo == "S") {
                        if ($tarefario->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
                            $retencao_fonte = $valor_ * $entidade->empresa->taxa_retencao_fonte / 100;
                        }
                    } else {
                        $retencao_fonte = 0;
                    }


                    ItemVenda::create([
                        "produto_id" => $tarefario->id,
                        "movimento_id" => 1,
                        "quantidade" => ($request->total_dias_reservado ?? 1),
                        'quantidade_devolvida' => 0,
                        "user_id" => Auth::user()->id,
                        "valor_pagar" => $valorBase + $valorIva,
                        "preco_unitario" => $tarefario->preco_venda,
                        "custo" => $tarefario->preco_custo * ($request->total_dias_reservado ?? 1),
                        "lucro" => ($tarefario->preco_venda * ($request->total_dias_reservado ?? 1)) - ($tarefario->preco_custo * ($request->total_dias_reservado ?? 1)),
                        "desconto_aplicado" => 0,
                        "retencao_fonte" => $retencao_fonte,
                        "status" => "processo",
                        "valor_base" => $valorBase,
                        "valor_iva" => $valorIva,
                        "desconto_aplicado_valor" => 0,
                        "iva" => $tarefario->imposto,
                        "iva_taxa" => $tarefario->taxa,
                        "texto_opcional" => "",
                        "status_uso" => $status_uso,
                        "caixa_id" => $caixa_id,
                        "banco_id" => $banco_id,
                        "mesa_id" => $mesa_id,
                        "quarto_id" => $item['quarto_id'],
                        "code" => NULL,
                        "numero_serie" => "",
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                }

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

                $data_emissao = $request->data_emissao . " " . date('H:i:s');
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
                    'lucro_total' => $lucro_total,
                    'custo_total' => $custo_total,
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'valor_divida' => 0,
                    'total_retencao_fonte' => $totalRetencao,
                    'valor_pago' => 0,
                    'ano_factura' => $entidade->empresa->ano_factura,
                    'prazo' => 0,
                    'valor_troco' => $request->valor_entregue - $tot___,
                    'data_emissao' => $request->data_emissao,
                    'data_documento' => $datactual,
                    'data_vencimento' => $request->data_emissao,
                    'data_disponivel' => $request->data_emissao,
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
                            'status' => true,
                            'movimento' => 'S',
                            'credito' => $tot___,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                            'debito' => 0,
                            'observacao' => "prestação de serviços hospitalares",
                            'code' => $code,
                            'data_at' => $request->data_emissao,
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
                $divida = $total;
                $pagamento = 'NAO EFECTUADO';
                $valor_pago = 0;
                $valor_troco = 0;
                $subconta_id = NULL;
            }

            // =========================================
            // VALIDAÇÕES INICIAIS
            // =========================================

            $dataEntrada = Carbon::parse($request->data_entrada);
            $dataSaida   = Carbon::parse($request->data_saida);

            $horaEntrada = $request->hora_entrada;
            $horaSaida   = $request->hora_saida;

            // Data saída menor que entrada
            if ($dataSaida->lt($dataEntrada)) {
                return response()->json([
                    'message' => 'A data de saída não pode ser menor que a data de entrada.'
                ], 404);
            }

            // Mesmo dia -> validar horas
            if ($dataEntrada->equalTo($dataSaida)) {

                // Hora igual
                if ($horaEntrada == $horaSaida) {
                    return response()->json([
                        'message' => 'A hora de entrada não pode ser igual à hora de saída.'
                    ], 404);
                }

                // Hora saída menor
                if ($horaSaida < $horaEntrada) {
                    return response()->json([
                        'message' => 'A hora de saída não pode ser menor que a hora de entrada.'
                    ], 404);
                }
            }

            // =========================================
            // CRIAR RESERVA
            // =========================================


            $reserva = Reserva::create([
                'codigo_referencia' => Reserva::gerarCodigoReferencia(),
                'valor_total' => $total,
                'valor_pago' => $valor_pago,
                'valor_divida' =>  $divida,
                'valor_troco' => $valor_troco,
                'valor_retencao_fonte' => $total * (($entidade->empresa->taxa_retencao_fonte ?? 0) / 100),
                
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                
                'total_pessoas' => NULL,
                'subconta_id' => $subconta_id,
                'forma_pagamento_id' => $forma_pagamento ? $forma_pagamento->id : NULL,

                'hora_entrada' => $request->hora_entrada,
                'hora_saida' => $request->hora_saida,

                'motivo_reserva_id' => NULL,
                'criancas' => NULL,
                'numero_criancas' => NULL,
                'observacao' => $request->observacao,

                'tipo_reserva_id' => $request->tipo_reserva_id,
                'data_inicio' => $request->data_entrada,
                'data_final' => $request->data_saida,
                'data_registro' => $dataAtual,
                'total_dias' => ($request->total_dias_reservado ?? 1),
                'code' => $code,
                'cliente_id' => $request->cliente_id,
                'status' => "PENDENTE",
                'exercicio_id' => $this->exercicio(),
                'periodo_id' => $this->periodo(),
                'pagamento' => $pagamento,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            foreach ($request->quartos as $item) {

                $quartoId = $item['quarto_id'];

                $tarifario = Produto::findOrFail($item['tarifario_id']);

                // =========================================
                // BUSCAR QUARTO
                // =========================================

                $quarto = Quarto::findOrFail($quartoId);

                // =========================================
                // VALIDAR ESTADO DO QUARTO
                // =========================================

                if ($quarto->solicitar_ocupacao == 'OCUPADO') {

                    return response()->json([
                        'message' => "O quarto {$quarto->numero} encontra-se ocupado."
                    ], 422);
                }

                $conflito = Quarto::where('id', $quartoId)

                    ->whereIn('solicitar_ocupacao', [
                        'RESERVADA',
                        'OCUPADA',
                    ])

                    // =====================================
                    // CONFLITO DE DATAS
                    // =====================================

                    ->where(function ($q) use ($request) {

                        $entrada = $request->data_entrada;
                        $saida   = $request->data_saida;

                        // SOBREPOSIÇÃO DE DATAS
                        $q->where('data_inicio', '<=', $saida)
                            ->where('data_final', '>=', $entrada);
                    })

                    // =====================================
                    // MESMO DIA -> VALIDAR HORAS
                    // =====================================

                    ->when(
                        $request->data_entrada == $request->data_saida,
                        function ($query) use ($request) {

                            $query->where(function ($q) use ($request) {

                                $horaEntrada = $request->hora_entrada;
                                $horaSaida   = $request->hora_saida;

                                // SOBREPOSIÇÃO DE HORAS
                                $q->where('hora_entrada', '<', $horaSaida)
                                    ->where('hora_saida', '>', $horaEntrada);
                            });
                        }
                    )

                    ->exists();

                if ($conflito) {

                    return response()->json([
                        'message' => "O quarto {$quarto->numero} já possui uma reserva neste período."
                    ], 404);
                }

                $quarto->solicitar_ocupacao = "RESERVADA";
                $quarto->hora_entrada = $request->hora_entrada;
                $quarto->hora_saida = $request->hora_saida;
                $quarto->data_inicio = $request->data_entrada;
                $quarto->data_final =  $request->data_saida;
                $quarto->code = $code;
                $quarto->update();

                ItemReserva::create([
                    'reserva_id' => $reserva->id,
                    'tarefario_id' => $tarifario->id,
                    'total_pessoas' => 0,
                    'valor_unitario' => 0,
                    'total_dias' => ($request->total_dias_reservado ?? 1),
                    'cliente_id' => $request->cliente_id,
                    'quarto_id' => $quarto->id,
                    'valor' => $tarifario->preco_venda * ($request->total_dias_reservado ?? 1),
                    'numero_criancas' => NULL,
                    'code' => $code,
                    'status' => 'concluido',
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }


            if ($request->fazer_check == "sim") {
                if ($request->data_entrada != date("Y-m-d")) {
                    return response()->json(['success' => true, 'message' => "Por favor, verifique a data de entrada da hospedagem do cliente registrada na reserva. A data de entrada informada na reserva não corresponde a hoje, mas sim a: {$reserva->data_inicio}!"], 404);
                }

                $reserva->user_check_in = $user->id;
                $reserva->data_check_in = date("Y-m-d");
                $reserva->hora_check_in = date("h:i:s");
                $reserva->check = 'IN';
                $reserva->status = 'EM USO';

                foreach ($request->quartos as $item) {
                    $quarto = Quarto::findOrFail($item['quarto_id']);
                    $quarto->solicitar_ocupacao = "OCUPADA";
                    $quarto->update();
                }

                $reserva->update();
            }

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'success' => true,
            'message' => "Dados Salvos com sucesso!",
            'reserva' => $reserva,
            'pdf_url_factura' => $create_factura ? route('factura-recibo', $create_factura->code) : NULL,
            'pdf_url' => route('imprimir-ficha-reservas', $reserva->id)
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

        $reserva = Reserva::with([
            "subconta",
            "exercicio",
            "items",
            "periodo",
            "motivo",
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

        return view('dashboard.reservas.show', $head);
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

            $reserva = Reserva::with(['items'])->findOrFail($id);

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', '=', Auth::user()->id)
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

            foreach ($reserva->items as $item) {
                $quarto = Quarto::findOrFail($item['quarto_id']);
                $quarto->hora_entrada = NULL;
                $quarto->hora_saida = NULL;
                $quarto->data_inicio = NULL;
                $quarto->data_final =  NULL;
                $quarto->solicitar_ocupacao = "LIVRE";
                $quarto->code = NULL;
                $quarto->update();
            }

            $reserva->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
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

            $reserva = Reserva::with(['items'])->findOrFail($id);

            if ($reserva->data_inicio != date("Y-m-d")) {
                return response()->json(['success' => true, 'message' => "Por favor, verifique a data de início da hospedagem do cliente registrada na reserva. A data de entrada informada na reserva não corresponde a hoje, mas sim a: {$reserva->data_inicio}!"], 404);
            }

            $reserva->user_check_in = $user->id;
            $reserva->data_check_in = date("Y-m-d");
            $reserva->hora_check_in = date("h:i:s");
            $reserva->check = 'IN';
            $reserva->status = 'EM USO';

            foreach ($reserva->items as $item) {
                $quarto = Quarto::findOrFail($item['quarto_id']);
                $quarto->solicitar_ocupacao = "OCUPADA";
                $quarto->update();
            }

            $reserva->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
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

            $reserva = Reserva::with(['items'])->findOrFail($id);

            if ($reserva->data_final >= date("Y-m-d")) {
                return response()->json(['success' => true, 'message' => "Por favor, verifique a data da saída do cliente na hospedagem registrada na reserva. A data de saída informada na reserva não corresponde a hoje, mas sim a: {$reserva->data_final}!"], 404);
            }

            if ($reserva->pagamento == "NAO EFECTUADO") {
                return response()->json(['success' => true, 'message' => "Não podes fazer o check por a reserva ainda não foi paga!"], 404);
            }

            $reserva->user_check_out = $user->id;
            $reserva->data_check_out = date("Y-m-d");
            $reserva->hora_check_out = date("h:i:s");
            $reserva->check = 'OUT';
            $reserva->status = 'SUCESSO';

            foreach ($reserva->items as $item) {

                $quarto = Quarto::findOrFail($item['quarto_id']);

                $vendas = Itemvenda::where('quarto_id', $quarto->id)->where('status', 'processo')->first();

                if ($vendas) {
                    return response()->json(['success' => true, 'status' => 401, 'message' => "Por favor, tens contas pendente a pagar então não podemos fazer o check out", 'redirect' => route('pronto-venda-mesas-quartos', Crypt::encrypt($quarto->id))], 200);
                }

                $quarto->solicitar_ocupacao = "LIVRE";
                $quarto->code = NULL;
                $quarto->hora_entrada = NULL;
                $quarto->hora_saida = NULL;
                $quarto->data_inicio = NULL;
                $quarto->data_final =  NULL;
                $quarto->update();
            }


            $reserva->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
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

        $reserva = Reserva::findOrFail($id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'reservas'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $exercicios = Exercicio::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $periodos = Periodo::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->whereIn('solicitar_ocupacao', ['LIVRE', 'RESERVADA'])
            ->get();

        $tarefarios = Produto::where("aplicado", "Y")->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $quartos = Quarto::where('entidade_id', '=', $entidade->empresa->id)
            ->where('solicitar_ocupacao', 'LIVRE')
            ->get();

        $motivos = MotivoReserva::where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('id', 'asc')
            ->get();

        $tipo_reservas = TipoReserva::where('entidade_id', $entidade->empresa->id)
            ->get();


        $forma_pagamentos = TipoPagamento::get();

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "reserva" => $reserva,
            "exercicios" => $exercicios,
            "quartos" => $quartos,
            "tarefarios" => $tarefarios,
            "quartos" => $quartos,
            "forma_pagamentos" => $forma_pagamentos,
            "periodos" => $periodos,
            "clientes" => $clientes,
            "motivos" => $motivos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.reservas.edit', $head);
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
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui


            $reserva = Reserva::findOrFail($id);

            $reserva->valor_divida = $request->total_factura;
            $reserva->valor_pago = 0;
            $reserva->valor_troco = 0;
            $reserva->valor_total = $request->total_factura;
            $reserva->valor_unitario = $request->preco_unitario;
            $reserva->valor_retencao_fonte = $request->total_factura * (6.5 / 100);
            $reserva->tarefario_id = $request->tarefario_id;
            $reserva->data_inicio = $request->data_entrada;
            $reserva->data_final = $request->data_saida;
            $reserva->hora_entrada = $request->hora_entrada;
            $reserva->hora_saida = $request->hora_saida;


            $reserva->motivo_reserva_id = $request->motivo_reserva_id;
            $reserva->criancas = $request->criancas;
            $reserva->numero_criancas = $request->numero_criancas;
            $reserva->observacao = $request->observacao;

            $reserva->total_dias = $request->total_dias;
            $reserva->cliente_id = $request->cliente_id;
            $reserva->quarto_id = $request->quarto_id;
            $reserva->exercicio_id = $request->exercicio_id;
            $reserva->periodo_id = $request->periodo_id;
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
            $reserva = Reserva::findOrFail($id);
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
        $reserva = Reserva::with([
            "subconta",
            "exercicio",
            "items",
            "periodo",
            "motivo",
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
            "reserva" => $reserva,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.reservas.ficha-reserva', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
