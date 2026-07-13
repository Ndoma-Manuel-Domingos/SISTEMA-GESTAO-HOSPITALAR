<?php

namespace App\Http\Controllers\pdf;

use App\Exports\ListagemProdutoExport;
use App\Exports\MapaRetencaoFonteExport;
use App\Exports\MovimentoEstoqueEntradaSaidaExport;
use App\Exports\MovimentoEstoqueExport;
use App\Exports\ProdutoEstoqueExport;
use App\Exports\VendaPorOperadorExport;
use App\Exports\VendaPorProdutoExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Desconto;
use App\Models\Entidade;
use App\Models\Exercicio;
use App\Models\Funcionario;
use App\Models\ItemVenda;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Periodo;
use App\Models\Processamento;
use App\Models\Produto;
use App\Models\Quarto;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\Reserva;
use App\Models\Subsidio;
use App\Models\TipoProcessamento;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Venda;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PDFController extends Controller
{

    use TraitHelpers;

    public function imprimirProcessamentos(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $processamentos_orgacao_social = Processamento::with(
            [
                'exercicio',
                'periodo',
                'funcionario.contrato.subsidios_contrato',
                'funcionario.contrato.descontos_contrato',
                'funcionario.contrato.categoria',
                'funcionario.contrato.cargo',
                'processamento',
                'user'
            ]
        )
            ->when($request->processamento_id, function ($query, $value) {
                $query->where('processamento_id', $value);
            })
            ->when($request->exercicio_id, function ($query, $value) {
                $query->where('exercicio_id', $value);
            })
            ->when($request->periodo_id, function ($query, $value) {
                $query->where('periodo_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->where('categoria', "Orgão Sociais")
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $processamentos_pessoal = Processamento::with(['exercicio', 'periodo', 'funcionario.contrato.categoria', 'funcionario.contrato.cargo', 'processamento', 'user'])
            ->when($request->processamento_id, function ($query, $value) {
                $query->where('processamento_id', $value);
            })
            ->when($request->exercicio_id, function ($query, $value) {
                $query->where('exercicio_id', $value);
            })
            ->when($request->periodo_id, function ($query, $value) {
                $query->where('periodo_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->whereIn('categoria', ["Pessoal", "Empregados"])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $processamentos = Processamento::with(['exercicio', 'periodo', 'funcionario.contrato.categoria', 'funcionario.contrato.cargo', 'processamento', 'user'])
            ->when($request->processamento_id, function ($query, $value) {
                $query->where('processamento_id', $value);
            })
            ->when($request->exercicio_id, function ($query, $value) {
                $query->where('exercicio_id', $value);
            })
            ->when($request->periodo_id, function ($query, $value) {
                $query->where('periodo_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $tipo_processamento = TipoProcessamento::find($request->processamento_id);

        $exercicio = Exercicio::find($request->exercicio_id);

        $periodo = Periodo::find($request->periodo_id);

        $subsidios = Subsidio::where('status', 'activo')->where('entidade_id',  $entidade->empresa->id)->get();
        $descontos = Desconto::where('status', 'activo')->where('entidade_id',  $entidade->empresa->id)->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => 'Processamentos',
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "empresa" => $empresa,
            "processamentos" => $processamentos,
            "processamentos_orgacao_social" => $processamentos_orgacao_social,
            "processamentos_pessoal" => $processamentos_pessoal,
            "tipo_processamento" => $tipo_processamento,
            "periodo" => $periodo,
            "exercicio" => $exercicio,

            "subsidios" => $subsidios,
            "descontos" => $descontos,

            "lojas" => $lojas,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id', 'processamento_id', 'exercicio_id', 'periodo_id', 'status'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.processamentos.imprimir', $head);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
    //
    public function imprimirRecibosProcessamentos(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $processamentos = Processamento::with([
            'exercicio',
            'periodo',
            'funcionario.contrato.forma_pagamento',
            'funcionario.contrato.categoria',
            'funcionario.contrato.pacote_salarial.subsidios_pacotes.subsidio',
            'funcionario.contrato.cargo.departamento',
            'funcionario.contrato.tipo_contrato',
            'processamento',
            'user'
        ])
            ->when($request->processamento_id, function ($query, $value) {
                $query->where('processamento_id', $value);
            })
            ->when($request->exercicio_id, function ($query, $value) {
                $query->where('exercicio_id', $value);
            })
            ->when($request->periodo_id, function ($query, $value) {
                $query->where('periodo_id', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => "Recibos",
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "empresa" => $empresa,
            "processamentos" => $processamentos,

            "lojas" => $lojas,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id', 'processamento_id', 'exercicio_id', 'periodo_id', 'status'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.processamentos.recibos', $head);


        return $pdf->stream();
    }


    public function pdfClientes(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $clientes = Cliente::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->where("entidade_id", $entidade->empresa->id)
            ->orderBy('conta', 'asc')->get();

        $empresa = Entidade::with(["tipo_entidade", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $titulo = "";

        if ($empresa->tipo_entidade->sigla == "CFOR") {
            $titulo = "ALUNOS";
        }
        if ($empresa->tipo_entidade->sigla == "HOTL") {
            $titulo = "HOSPEDES";
        }
        if ($empresa->tipo_entidade->sigla == "CONS") {
            $titulo = "PACIENTES";
        }
        if ($empresa->tipo_entidade->sigla == "HOSP") {
            $titulo = "PACIENTES";
        }
        if ($empresa->tipo_entidade->sigla == "CFAT") {
            $titulo = "CLIENTES";
        }

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'titulo' => "RELATÓRIO DE " . $titulo,
            'descricao' => "",
            'clientes' => $clientes,
            "empresa" => $empresa,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.clientes.pdf-clientes', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdfFuncionarios(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])
            ->where([
                ['entidade_id', '=', $entidade->empresa->id],
            ])
            ->orderBy('conta', 'asc')
            ->get();

        $empresa = Entidade::with(["tipo_entidade", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => __('messages.listagem'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => "",
            'funcionarios' => $funcionarios,
            "empresa" => $empresa,
            "requests" => $request->all('hora_entrada', 'hora_saida', 'data_inicio', 'data_final', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id'),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.funcionarios.pdf-funcionarios', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdfReserva(Request $request)
    {
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

            ->when($request->status_reserva, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->status_pagamento, function ($query, $value) {
                $query->where('pagamento', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_inicio', $value);
            })
            ->with([
                'items.quarto',
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

        $cliente = Cliente::find($request->cliente_id);
        $quarto = Quarto::find($request->quarto_id);


        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => __('messages.listagem'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => "",
            'cliente' => $cliente,
            'quarto' => $quarto,
            'reservas' => $reservas,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'cliente_id', 'status_reserva', 'status_pagamento', 'quarto_id', 'tipo_reserva_id'),
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.reservas.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    public function pdfProduto(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $query = Produto::whereIn("id", $meus_produtos)
            ->when($request->categoria_id, function ($query, $value) {
                $query->where('categoria_id', $value);
            })->when($request->marca_id, function ($query, $value) {
                $query->where('marca_id', $value);
            });

        if ($request->tipo == "materia-prima") {
            $query->where('tipo_stock', 'P');
        } else {
            $query->when($request->tipo, function ($query, $value) {
                $query->where('tipo', $value);
            });
        }

        $produtos = $query->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => __('messages.listagem'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => env('APP_NAME'),
            'produtos' => $produtos,
            "empresa" => $empresa,
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.produtos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    public function pdfProdutoExcel(Request $request)
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $codigo = date("Y-m-d");

        return Excel::download(new ListagemProdutoExport($request->categoria_id, $request->tipo, $request->marca_id), "listagem-produtos-{$codigo}.xlsx");
    }

    public function pdfRegistroMovimentoEstoque(Request $request, $id)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $registro = RegistroMovimento::with(["user", "loja", "items.produto.unidade", "items.lote"])->findOrFail($id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $titulo = $registro->operacao == "Saída de Stock" ? "RELATÓRIO DE SAÍDA DE PRODUTOS" : "RELATÓRIO DE ENTRADA DE PRODUTOS";

        $head = [
            "titulo" => $titulo,
            "descricao" => env("APP_NAME"),
            "registro" => $registro,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa" => $empresa,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView("dashboard.vendas.registros-movimentos", $head);
        $pdf->setPaper("A4", "portrait");

        return $pdf->stream();
    }

    public function pdfVendas(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $total_arrecadado = ItemVenda::with(['produto', 'factura.loja'])->when($request->data_inicio, function ($query, $value) {
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
            ->where('status', "!=", "anulada")
            ->where('entidade_id', $empresa->id)
            ->get();

        $total_vendido_valor = 0;
        $total_Custo_produto_vendido = 0;
        $total_ganho_vendas = 0;
        $total_arrecadado_cash = 0;
        $total_arrecadado_multicaixa = 0;
        $total_arrecadado_transferencias = 0;
        $total_arrecadado_depositos = 0;
        $total_duplo = 0;

        foreach ($total_arrecadado as $valores) {

            $total_vendido_valor += $valores->valor_pagar;
            $total_Custo_produto_vendido += $valores->custo;
            $total_ganho_vendas += $valores->lucro;

            if ($valores->factura) {
                if ($valores->factura->pagamento == "NU") {
                    $total_arrecadado_cash += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "MB") {
                    $total_arrecadado_multicaixa += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "OU") {
                    $total_arrecadado_cash += $valores->valor_cash;
                    $total_arrecadado_multicaixa += $valores->valor_multicaixa;
                    $total_duplo += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "TE") {
                    $total_arrecadado_transferencias += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "DE") {
                    $total_arrecadado_depositos += $valores->valor_pagar;
                }
            }
        }


        $caixa = Caixa::find($request->caixa_id);
        $user = User::find($request->user_id);
        $loja = Loja::find($request->loja_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => __('messages.listagem'),
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "total_vendido_valor" => $total_vendido_valor,
            "total_Custo_produto_vendido" => $total_Custo_produto_vendido,
            "total_ganho_vendas" => $total_ganho_vendas,
            "total_arrecadado_cash" => $total_arrecadado_cash,
            "total_arrecadado_multicaixa" => $total_arrecadado_multicaixa,
            "total_arrecadado_transferencias" => $total_arrecadado_transferencias,
            "total_arrecadado_depositos" => $total_arrecadado_depositos,
            "total_duplo" => $total_duplo,

            'vendas' => $total_arrecadado,
            "caixa" => $caixa,
            "user" => $user,
            "loja" => $loja,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final', 'caixa_id', 'user_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function imprimirPdfVendas(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $vendas = ItemVenda::with(['produto', 'user', 'factura.cliente'])
            ->select(
                'produto_id',
                DB::raw("SUM(quantidade) as total_quantidade"),
                DB::raw("SUM(quantidade_devolvida) as total_quantidade_devolvidas"),
                DB::raw("SUM(lucro) as total_lucro"),
                DB::raw("SUM(custo) as total_custo"),
                DB::raw("SUM(valor_pagar) as total_valor"),
                DB::raw("SUM(iva_taxa) as total_iva")
            )
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['realizado'])
            ->whereHas('factura', function ($query) {
                $query->whereIn('status_factura', ['pago']);
            })
            ->whereHas("produto", function ($query) use ($request) {
                if ($request->categoria_id) {
                    $query->where("categoria_id", $request->categoria_id);
                };
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
            ->groupBy('produto_id')
            ->get()
            ->sortBy(function ($prod) {
                return $prod->produto->nome ?? '';
            });

        $caixa = Caixa::find($request->caixa_id);
        $user = User::find($request->user_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => __('messages.listagem'),
            'descricao' => env('APP_NAME'),
            'total_venda' => 0,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'vendas' => $vendas,
            "caixa" => $caixa,
            "user" => $user,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final', 'caixa_id', 'user_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.pdf-por-produto', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return view('dashboard.vendas.pdf-por-produto', $head);
    }

    public function imprimirPdfVendasExcel(Request $request)
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $codigo = date("Y-m-d");

        return Excel::download(new VendaPorProdutoExport($request->data_inicio, $request->data_final, $request->caixa_id, $request->user_id, $request->categoria_id), "vendas-por-produtos-{$codigo}.xlsx");
    }

    public function imprimirPdfMapaRetencaoFonte(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $vendas = ItemVenda::with(["produto.categoria", "user", "factura.cliente"])
            ->select(
                "produto_id",
                DB::raw("SUM(retencao_fonte) as total_retencao_fonte"),
                DB::raw("SUM(valor_pagar) as total_valor_pagar"),
            )
            ->where("entidade_id", $entidade->empresa->id)
            ->where("status", "realizado")
            ->whereHas("factura", function ($query) use ($request) {
                $query->when($request->status, function ($query, $value) {
                    $query->where("status_factura", $value);
                });
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
            })
            ->groupBy('produto_id')
            ->get()
            ->sortBy(function ($prod) {
                return $prod->produto->nome ?? '';
            });

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => "MAPA DE RETENÇÃO NA FONTE",
            'descricao' => env('APP_NAME'),
            'total_venda' => 0,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'vendas' => $vendas,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final', 'status'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.mapa-retencao-fonte', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
        // return view('dashboard.vendas.pdf-por-produto', $head);
    }

    public function imprimirPdfMapaRetencaoFonteExcel(Request $request)
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $codigo = date("Y-m-d");

        return Excel::download(new MapaRetencaoFonteExport($request->data_inicio, $request->data_final, $request->status), "mapa-retencao-fonte-{$codigo}.xlsx");
    }


    public function imprimirPdfVendasOperadores(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $vendas = ItemVenda::with(['produto', 'user'])
            ->select(
                'user_id',
                DB::raw("SUM(valor_pagar) as total_valor"),
            )
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['realizado'])
            ->whereHas('factura', function ($query) {
                $query->whereIn('status_factura', ['pago']);
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
            ->groupBy('user_id')
            ->get()
            ->sortBy(function ($prod) {
                return $prod->produto->nome ?? '';
            });

        $caixa = Caixa::find($request->caixa_id);
        $user = User::find($request->user_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => "RELATÓRIO DE VENDAS POR OPERADORES",
            'descricao' => env('APP_NAME'),
            'total_venda' => 0,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'vendas' => $vendas,
            "caixa" => $caixa,
            "user" => $user,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final', 'caixa_id', 'user_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.pdf-vendas-por-operadores', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function imprimirPdfVendasOperadoresExcel(Request $request)
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $codigo = date("Y-m-d");

        return Excel::download(new VendaPorOperadorExport($request->data_inicio, $request->data_final, $request->caixa_id, $request->user_id), "vendas-por-operadoes-{$codigo}.xlsx");
    }


    public function imprimirPdfMovimentoEstoque(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $movimentos = RegistroMovimento::with(['items.produto', 'items.lote'])
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::createFromDate($value));
            })
            ->whereHas('items', function ($query) use ($request) {
                $query->when($request->produto_id, function ($query, $value) {
                    $query->where('produto_id', $value);
                });
                // 🔹 Filtro adicional por categoria (aninhado no produto)
                $query->when($request->categoria_id, function ($query, $value) {
                    $query->whereHas('produto.categoria', function ($subQuery) use ($value) {
                        $subQuery->where('id', $value);
                    });
                });
            })
            ->when($request->fornecedor_id, function ($query, $value) {
                $query->where("fornecedor_id", $value);
            })
            ->when($request->cliente_id, function ($query, $value) {
                $query->where("cliente_id", $value);
            })
            ->when($request->tipo, function ($query, $value) {
                $query->where("tipo_documento", $value);
            })
            ->orderBy('data_at', 'asc')
            ->get();


        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $subtitulo = "";

        if ($request->tipo == "E") {
            $subtitulo = "ENTRADAS";
        } else if ($request->tipo == "S") {
            $subtitulo = "SAÍDAS";
        } else {
            $subtitulo = "";
        }

        $head = [
            'titulo' => "RELATÓRIO DE MOVIMENTOS DO STOCK {$subtitulo}",
            'descricao' => env('APP_NAME'),
            'total_venda' => 0,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'movimentos' => $movimentos,
            "empresa" => $empresa,
            "requests" => $request->all('data_inicio', 'data_final', 'fornecedor_id', 'cliente_id', 'produto_id', 'caixa_id', 'user_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.movimentos-estoque-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function imprimirPdfMovimentoEstoqueExcel(Request $request)
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $codigo = date("Y-m-d");

        return Excel::download(new MovimentoEstoqueEntradaSaidaExport(
            $request->data_inicio,
            $request->data_final,
            $request->tipo,
            $request->categoria_id,
            $request->fornecedor_id,
            $request->produto_id
        ), "movimentos-estoque-entradas-e-saidas-{$codigo}.xlsx");
    }


    public function pdfStockArtigo(Request $request, string $tipo = 'produto')
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $isMateriaPrima = $tipo === 'materias-primas';

        // Obter todos os produtos com suas vendas e Stock
        $produtos = Produto::with([
            'vendas' => function ($query) use ($request) {
                $query->when($request->data_inicio, function ($query, $value) {
                    $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
                })
                    ->when($request->data_final, function ($query, $value) {
                        $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
                    })
                    ->when($request->caixa_id, function ($query, $value) {
                        $query->where('caixa_id', '=', $value);
                    });
                $query->where("status", "!=", "anulada");
            },
            'stocks' => function ($query) use ($request) {
                $query->when($request->loja_id, function ($query, $value) {
                    $query->where("loja_id", $value);
                });
            }
        ])
            ->when(
                $isMateriaPrima,
                fn($q) => $q->where('tipo_stock', 'P'),
                fn($q) => $q->where('tipo_stock', '!=', 'P')
            )
            ->when($request->categoria_id, function ($query, $value) {
                $query->where("categoria_id", $value);
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('nome')
            ->get();

        $dados = $produtos->map(function ($produto) use ($request) {
            $dataInicio = $request->data_inicio ? Carbon::parse($request->data_inicio) : Carbon::now()->startOfDay();
            $dataFinal = $request->data_final ? Carbon::parse($request->data_final) : Carbon::now()->endOfDay();

            $totalCusto = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                ->sum('custo');

            $totalLucro = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                ->sum('lucro');

            $totalRetencaoAcumuada = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                // ->where('registro', 'Saída de Stock')
                ->sum('retencao_fonte');

            // totdal vendido
            $quantidadeVendida = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                // ->where('registro', 'Saída de Stock')
                ->sum('quantidade');

            $totalVendida = $produto->vendas
                ->whereBetween('created_at', [$dataInicio, $dataFinal])
                // ->where('registro', 'Saída de Stock')
                ->sum('valor_pagar');

            // Calcular a quantidade em estoque até a data final especificada
            // $quantidadeEmEstoque = $produto->stocks->where('created_at', '<=', $dataFinal)->sum('stock');

            $quantidadeEmEstoque = $produto->converterDaBase($produto->stocks->sum("stock"), $produto->unidade);

            // Calcular a quantidade restante
            $quantidadeRestante = $quantidadeEmEstoque - $quantidadeVendida;

            // Calcular a quantidade inicial
            $quantidadeInicial = $quantidadeEmEstoque + $quantidadeVendida;

            return (object) [
                'id' => $produto->id,
                'codigo_barra' => $produto->codigo_barra,
                'produto' => $produto->nome,
                'preco' => $produto->preco_venda,
                'preco_custo' => $produto->preco_custo,
                'unidade' => $produto->unidade,
                'imposto' => $produto->taxa,
                'tipo' => $produto->tipo,
                'desconto' => 0,
                'total_liquido_vendido' => $totalVendida,
                'total_liquido_restante' => $produto->preco_venda * $quantidadeInicial,
                'total_liquido_geral' => $produto->preco_custo * $quantidadeEmEstoque,
                'quantidade_inicial' => $quantidadeInicial,
                'quantidade_vendida' => $quantidadeVendida,
                'quantidade_estoque' => $quantidadeEmEstoque,
                'quantidade_restante' => $quantidadeRestante,
                'total_liquido_custo' => $totalCusto,
                'total_liquido_lucro' => $totalLucro,
                'totalRetencaoAcumuada' => $totalRetencaoAcumuada
            ];
        })
            ->when($request->filled('apenas_com_quantidade') && $request->apenas_com_quantidade == true, function ($collection) use ($request) {
                if ($request->apenas_com_quantidade == "true") {
                    return $collection->filter(fn($item) => $item->quantidade_inicial > 0);
                } else if ($request->apenas_com_quantidade == "false") {
                    return $collection->filter(fn($item) => $item->quantidade_inicial <= 0);
                } else {
                    return $collection->filter(fn($item) => $item->quantidade_inicial > 10);
                }
            })
            ->values();

        $loja = Loja::find($request->loja_id);
        $user = User::find($request->user_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => $isMateriaPrima ? 'Stock de Matérias-primas' : 'Stock de Produtos',
            "isMateriaPrima" => $isMateriaPrima,
            "descricao" => env("APP_NAME"),
            "dados" => $dados,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => $loja,
            "user" => $user,
            "requests" => $request->all('data_inicio', 'data_final', 'loja_id', 'user_id', 'tipo_preco'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.vendas.pdf-stock-artigo', $head);
        return $pdf->stream();
    }

    public function pdfStockArtigoExcel(Request $request, string $tipo)
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $codigo = date("Y-m-d");

        $isMateriaPrima = $tipo === 'materias-primas';

        return Excel::download(new ProdutoEstoqueExport($request, $isMateriaPrima), "produtos-no-stock-{$codigo}.xlsx");
    }

    public function pdfMovimentoEstoque(Request $request)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimentos = Registro::when($request->loja_id, function ($query, $value) {
            $query->where('loja_id', $value);
        })
            ->when($request->produto_id, function ($query, $value) {
                $query->where('produto_id', $value);
            })
            ->when($request->tipo, function ($query, $value) {
                $query->where('tipo', $value);
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->with('produto.unidade', 'user', 'loja')
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $produto = Produto::find($request->produto_id);
        $loja = Loja::find($request->loja_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $empresa = User::with("variacoes")->with(['empresa'])->with("categorias")->with("marcas")->findOrFail(Auth::user()->id);
        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => "",
            'movimentos' => $movimentos,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "produto" => $produto,
            "loja" => $loja,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
            "requests" => $request->all('loja_id', 'status', 'tipo', 'produto_id', 'data_inicio', 'data_final')
        ];

        $pdf = PDF::loadView('dashboard.estoques-movimentos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdfMovimentoEstoqueExcel(Request $request)
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $codigo = date("Y-m-d");

        return Excel::download(new MovimentoEstoqueExport($request->data_inicio, $request->data_final, $request->loja_id, $request->produto_id, $request->tipo, $request->status), "movimento-do-stock-{$codigo}.xlsx");
    }

    public function pdfMovimentoEstoqueLoja($id)
    {
        $loja = Loja::findOrFail($id);

        $movimentos = Registro::with('produto', 'user', 'loja')->where([
            ['registros.user_id', '=', Auth::user()->id],
            ['registros.produto_id', '=', $loja->id],
        ])
            ->orderBy('registros.created_at', 'desc')
            ->get();

        $empresa = User::with("variacoes")->with(['empresa'])->with("categorias")->with("marcas")->findOrFail(Auth::user()->id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => "Movimentos do Stock",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => env('APP_NAME'),
            'tituloPagina' => "Movimentos do Stock",
            'movimentos' => $movimentos,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.estoques-movimentos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdfMovimentoEstoqueProduto($id)
    {
        $produto = Produto::findOrFail($id);

        $movimentos = Registro::with('produto', 'user', 'loja')->where([
            ['registros.user_id', '=', Auth::user()->id],
            ['registros.produto_id', '=', $produto->id],
        ])
            ->orderBy('registros.created_at', 'desc')
            ->get();

        $empresa = User::with("variacoes")->with(['empresa'])->with("categorias")->with("marcas")->findOrFail(Auth::user()->id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);


        $head = [
            'titulo' => "Movimentos do Stock",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => env('APP_NAME'),
            'tituloPagina' => "Movimentos do Stock",
            'movimentos' => $movimentos,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.estoques-movimentos.pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function imprimirFactura()
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            'titulo' => "Movimentos do Stock",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function imprimirFacturaRecibo($id)
    {
        $vendas = Venda::with('cliente')->where('code', $id)->first();
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $items = ItemVenda::with('produto')->where('code', $vendas->code)->get();

        $head = [
            'titulo' => "Movimentos do Stock",
            'descricao' => env('APP_NAME'),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "factura" => $vendas,
            "items" => $items,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.facturas.documentos.factura-recibo', $head);
    }

    public function cliente_pdf(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $clientes = Cliente::with(['vendas.items.produto'])
            ->with(['vendas.items' => function ($query) use ($request) {
                // Filtrar as vendas por intervalo de datas se as datas forem fornecidas
                $query->when($request->data_inicio && $request->data_final, function ($query) use ($request) {
                    $query->whereBetween('created_at', [Carbon::parse($request->data_inicio), Carbon::parse($request->data_final)]);
                });
                $query->where('status', '!=', 'anulada');
            }])
            ->when($request->cliente_id, function ($query, $clienteId) {
                // Filtrar pelo cliente se um ID de cliente for fornecido
                $query->where('id', $clienteId);
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();


        // Preparar os dados para que os produtos não sejam duplicados
        $dadosClientes = $clientes->map(function ($cliente) {
            // Usar um array para consolidar os produtos
            $produtosAgrupados = [];

            foreach ($cliente->vendas as $venda) {
                foreach ($venda->items as $item) {

                    $produtoId = $item->produto->id;

                    // Se o produto já estiver no array, somar a quantidade e o valor
                    if (isset($produtosAgrupados[$produtoId])) {
                        $produtosAgrupados[$produtoId]['quantidade'] += $item->quantidade;
                        $produtosAgrupados[$produtoId]['valor_pagar'] += $item->valor_pagar;
                        $produtosAgrupados[$produtoId]['desconto_aplicado_valor'] += $item->desconto_aplicado_valor;
                        $produtosAgrupados[$produtoId]['custo'] += $item->custo;
                    } else {

                        // Se não estiver, adicionar ao array
                        $produtosAgrupados[$produtoId] = [
                            'produto' => $item->produto->nome,
                            'preco' => $item->produto->preco_venda,
                            'custo' => $item->produto->preco_custo,

                            'quantidade' => $item->quantidade,
                            'preco_unitario' => $item->preco_unitario,
                            'valor_pagar' => $item->valor_pagar,
                            'desconto_aplicado_valor' => $item->desconto_aplicado_valor,
                            'custo' => $item->custo,
                        ];
                    }
                }
            }

            // Retornar os dados do cliente com os produtos agrupados
            return (object) [
                'cliente' => $cliente->nome,
                'codigo' => $cliente->id,
                'produtos' => array_values($produtosAgrupados), // Usar array_values para retornar apenas os valores
            ];
        });

        $head = [
            "titulo" => "Relatórios de Clientes",
            "descricao" => env('APP_NAME'),
            "clientes" => $clientes,
            "dadosClientes" => $dadosClientes,
            "requests" => $request->all('data_inicio', 'data_final', 'cliente_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.relatorio', $head);
    }


    public function cliente_pdf_imprimir(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $cliente = Cliente::find($request->cliente_id);

        $clientes = Cliente::with(['vendas.items.produto'])
            ->with(['vendas.items' => function ($query) use ($request) {
                // Filtrar as vendas por intervalo de datas se as datas forem fornecidas
                $query->when($request->data_inicio && $request->data_final, function ($query) use ($request) {
                    $query->whereBetween('created_at', [Carbon::parse($request->data_inicio), Carbon::parse($request->data_final)]);
                });
                $query->where('status', '!=', 'anulada');
            }])
            ->when($request->cliente_id, function ($query, $clienteId) {
                // Filtrar pelo cliente se um ID de cliente for fornecido
                $query->where('id', $clienteId);
            })
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Preparar os dados para que os produtos não sejam duplicados
        $dadosClientes = $clientes->map(function ($cliente) {
            // Usar um array para consolidar os produtos
            $produtosAgrupados = [];

            foreach ($cliente->vendas as $venda) {
                foreach ($venda->items as $item) {

                    $produtoId = $item->produto->id;

                    // Se o produto já estiver no array, somar a quantidade e o valor
                    if (isset($produtosAgrupados[$produtoId])) {
                        $produtosAgrupados[$produtoId]['quantidade'] += $item->quantidade;
                        $produtosAgrupados[$produtoId]['valor_pagar'] += $item->valor_pagar;
                        $produtosAgrupados[$produtoId]['desconto_aplicado_valor'] += $item->desconto_aplicado_valor;
                        $produtosAgrupados[$produtoId]['custo'] += $item->custo;
                    } else {

                        // Se não estiver, adicionar ao array
                        $produtosAgrupados[$produtoId] = [
                            'produto' => $item->produto->nome,
                            'preco' => $item->produto->preco_venda,
                            'custo' => $item->produto->preco_custo,

                            'quantidade' => $item->quantidade,
                            'preco_unitario' => $item->preco_unitario,
                            'valor_pagar' => $item->valor_pagar,
                            'desconto_aplicado_valor' => $item->desconto_aplicado_valor,
                            'custo' => $item->custo,
                        ];
                    }
                }
            }

            // Retornar os dados do cliente com os produtos agrupados
            return (object) [
                'cliente' => $cliente->nome,
                'codigo' => $cliente->id,
                'produtos' => array_values($produtosAgrupados), // Usar array_values para retornar apenas os valores
            ];
        });


        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Relatórios de Compras dos Clientes",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "cliente" => $cliente,
            "dadosClientes" => $dadosClientes,
            "requests" => $request->all('data_inicio', 'data_final', 'cliente_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.clientes.pdf-compras', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }


    // public function exportProdutoExcel()
    // {
    //     return Excel::download(new ProdutoExport, 'produto.xlsx');
    // }

    // public function exportProdutoCsv()
    // {
    //     return Excel::download(new ProdutoExport, 'produto.csv');
    // }
}
