<?php

namespace App\Http\Controllers\app\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\Quarto;
use App\Models\Caixa;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\Subconta;
use App\Models\ContaBancaria;
use App\Models\ContaCliente;
use App\Models\Fornecedore;
use App\Models\ItemPedidoCuzinha;
use App\Models\OperacaoFinanceiro;
use App\Models\ItemVenda;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Mesa;
use App\Models\Movimento;
use App\Models\MovimentoContaCliente;
use App\Models\PedidoCuzinha;
use App\Models\Pin;
use App\Models\Produto;
use App\Models\ProdutoGrupoPreco;
use App\Models\Receita;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\Reserva;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Session;

use phpseclib\Crypt\RSA;
use Spatie\Permission\Models\Permission;

class VendaController extends Controller
{
    //
    use TraitChavesSaft;
    use TraitHelpers;

    public function vendas(Request $request)
    {
        $entidade = User::with(["empresa.lojas"])->findOrFail(Auth::user()->id);

        $vendas = Venda::with(["user", "cliente"])->where("entidade_id", $entidade->empresa->id)
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::createFromDate($value));
            })
            ->when($request->loja_id, function ($query, $value) {
                $query->where("loja_id", $value);
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where("caixa_id", $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where("user_id", $value);
            })
            ->orderBy("created_at", "desc")
            ->get();

        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Vendas",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "vendas" => $vendas,
            "entidade" => $entidade,
            "requests" => $request->all("data_inicio", "data_final", "caixa_id", "user_id", "loja_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];


        return view("dashboard.vendas.dashboard", $head);
    }

    public function vendas_produtos(Request $request)
    {
        $entidade = User::with(["empresa.caixas", "empresa.lojas"])->findOrFail(Auth::user()->id);

        $query = ItemVenda::with(["produto", "user", "factura" => function ($query) use ($request) {

            $query->when($request->caixa_id, function ($query, $value) {
                $query->where("caixa_id", $value);
            })
                ->when($request->user_id, function ($query, $value) {
                    $query->where("user_id", $value);
                })
                ->when($request->loja_id, function ($query, $value) {
                    $query->where("loja_id", $value);
                });
        }, "factura.cliente"])
            ->where("entidade_id", $entidade->empresa->id)
            ->whereIn("status", ["realizado"])
            ->whereHas("factura", function ($query) {
                $query->whereIn("status_factura", ["pago"]);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::createFromDate($value));
            })
            ->orderBy("created_at", "desc");

        $total_venda = $query->sum("valor_pagar");

        $vendas = $query->get();

        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Vendas",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "total_venda" => $total_venda,
            "vendas" => $vendas,
            "entidade" => $entidade,
            "requests" => $request->all("data_inicio", "data_final", "caixa_id", "user_id", "loja_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.dashboard-produtos", $head);
    }

    public function mapa_retencao_fonte(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $request->data_inicio = $request->data_inicio ?? date("Y-m-d");
        $request->data_final = $request->data_final ?? date("Y-m-d");

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
                $query->whereDate("created_at", ">=", Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::parse($value));
            })
            ->groupBy("produto_id")
            ->get()
            ->sortBy(function ($prod) {
                return $prod->produto->nome ?? '';
            });

        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "MAPA DE RETENÇÃO NA FONTE",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "total_venda" => 0,
            "vendas" => $vendas,
            "entidade" => $entidade,
            "requests" => $request->all("status", "data_inicio", "data_final"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.dashboard-mapa-retencao-fonte", $head);
    }

    public function vendas_por_produtos(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);


        $request->data_inicio = $request->data_inicio ?? date("Y-m-d");
        $request->data_final = $request->data_final ?? date("Y-m-d");

        $vendas = ItemVenda::with(["produto.categoria", "user", "factura.cliente"])
            ->select(
                "produto_id",
                DB::raw("SUM(quantidade) as total_quantidade"),
                DB::raw("SUM(quantidade_devolvida) as total_quantidade_devolvidas"),
                DB::raw("SUM(lucro) as total_lucro"),
                DB::raw("SUM(custo) as total_custo"),
                DB::raw("SUM(valor_pagar) as total_valor"),
                DB::raw("SUM(iva_taxa) as total_iva")
            )
            ->where("entidade_id", $entidade->empresa->id)
            ->where("status", "realizado")
            ->whereHas("factura", function ($query) {
                $query->where("status_factura", "pago");
            })
            ->whereHas("produto", function ($query) use ($request) {
                if ($request->categoria_id) {
                    $query->where("categoria_id", $request->categoria_id);
                };
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::parse($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::parse($value));
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where("caixa_id", $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where("user_id", $value);
            })
            ->groupBy("produto_id")
            ->get()
            ->sortBy(function ($prod) {
                return $prod->produto->nome ?? '';
            });

        $total = 0;
        foreach ($vendas as $item) {
            $total += $item->preco_total;
        }

        $categorias = Categoria::where("entidade_id", $entidade->empresa->id)->get();
        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => env("APP_NAME") . " Pronto de Vendas",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "total_venda" => 0,
            "vendas" => $vendas,
            "entidade" => $entidade,
            "categorias" => $categorias,
            "requests" => $request->all("data_inicio", "data_final", "caixa_id", "user_id", "categoria_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.dashboard-por-produtos", $head);
    }

    public function vendas_por_operadores(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $request->data_inicio = $request->data_inicio ?? date("Y-m-d");
        $request->data_final = $request->data_final ?? date("Y-m-d");

        $vendas = ItemVenda::with(["produto", "user", "factura.cliente"])
            ->select(
                "user_id",
                DB::raw("SUM(quantidade) as total_quantidade"),
                DB::raw("SUM(quantidade_devolvida) as total_quantidade_devolvidas"),
                DB::raw("SUM(lucro) as total_lucro"),
                DB::raw("SUM(custo) as total_custo"),
                DB::raw("SUM(valor_pagar) as total_valor"),
                DB::raw("SUM(iva_taxa) as total_iva")
            )
            ->where("entidade_id", $entidade->empresa->id)
            ->whereIn("status", ["realizado"])
            ->whereHas("factura", function ($query) {
                $query->whereIn("status_factura", ["pago"]);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::createFromDate($value));
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where("caixa_id", $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where("user_id", $value);
            })
            ->groupBy("user_id")
            ->get();

        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => env("APP_NAME") . " Pronto de Vendas",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "total_venda" => 0,
            "vendas" => $vendas,
            "entidade" => $entidade,
            "requests" => $request->all("data_inicio", "data_final", "caixa_id", "user_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.dashboard-por-operador", $head);
    }

    public function vendas_valorizacao_estoque(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $request->data_inicio = $request->data_inicio ?? date("Y-m-d");
        $request->data_final = $request->data_final ?? date("Y-m-d");

        $movimentacoes = Registro::with('produto')->orderBy('data_registro', 'asc')
            ->get()
            ->groupBy('produto_id');

        $resultado = [];

        foreach ($movimentacoes as $produtoId => $movs) {
            // FIFO (ordem de entrada)
            $fifoMovs = $movs->sortBy('data_registro')->values();
            // LIFO (ordem inversa de entrada)
            $lifoMovs = $movs->sortByDesc('data_registro')->values();

            $estoqueFIFO = 0;
            $custoFIFO = 0;

            $estoqueLIFO = 0;
            $custoLIFO = 0;

            foreach ($fifoMovs as $m) {
                if ($m->tipo === 'E') {
                    $estoqueFIFO += $m->quantidade;
                    $custoFIFO += $m->quantidade * $m->produto->preco_venda;
                } else {
                    $estoqueFIFO -= $m->quantidade;
                    $custoFIFO -= $m->quantidade * $fifoMovs->first()->produto->preco_venda;
                }
            }

            foreach ($lifoMovs as $m) {
                if ($m->tipo === 'S') {
                    $estoqueLIFO += $m->quantidade;
                    $custoLIFO += $m->quantidade * $m->produto->preco_venda;
                } else {
                    $estoqueLIFO -= $m->quantidade;
                    $custoLIFO -= $m->quantidade * $lifoMovs->first()->produto->preco_venda;
                }
            }

            $resultado[] = [
                'produto' => Produto::findOrFail($produtoId),
                'estoque_fifo' => $estoqueFIFO,
                'valor_fifo' => $custoFIFO,
                'estoque_lifo' => $estoqueLIFO,
                'valor_lifo' => $custoLIFO,
            ];
        }

        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => env("APP_NAME") . " Pronto de Vendas",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "total_venda" => 0,
            "resultado" => $resultado,
            "entidade" => $entidade,
            "requests" => $request->all("data_inicio", "data_final", "caixa_id", "user_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.dashboard-valorizacao-estoque", $head);
    }

    public function vendas_movimentos_estoques(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $request->data_inicio = $request->data_inicio ?? date("Y-m-d");
        $request->data_final = $request->data_final ?? date("Y-m-d");

        $movimentos = RegistroMovimento::with(['items.produto.categoria', 'fornecedor', 'cliente'])
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::createFromDate($value));
            })
            ->when($request->tipo, function ($query, $value) {
                $query->where("tipo", $value);
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
            ->orderBy('data_at', 'asc')
            ->get();


        $fornecedores = Fornecedore::where("entidade_id", $entidade->empresa->id)->get();
        $clientes = Cliente::where("entidade_id", $entidade->empresa->id)->get();
        $produtos = Produto::where("entidade_id", $entidade->empresa->id)->get();
        $categorias = Categoria::where("entidade_id", $entidade->empresa->id)->get();

        $empresa = Entidade::with(["caixas", "users"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Movimentos de stock entradas e saídas",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "movimentos" => $movimentos,
            "clientes" => $clientes,
            "fornecedores" => $fornecedores,
            "produtos" => $produtos,
            "entidade" => $entidade,
            "categorias" => $categorias,
            "requests" => $request->all("data_inicio", "data_final", "tipo", "categoria_id", 'fornecedor_id', 'cliente_id', 'produto_id'),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.dashboard-movimentos-estoque", $head);
    }

    public function vendas_por_artigo(Request $request, string $tipo = 'produto')
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $isMateriaPrima = $tipo === 'materias-primas';

        $produtos = Produto::with(["unidade", "vendas" => function ($query) use ($request) {
            // Filtrar as vendas com base nas datas fornecidas pelo usuário
            $query->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::parse($value));
            })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate("created_at", "<=", Carbon::parse($value));
                });
            $query->where("status", "!=", "anulada");
        }, 
        "stocks" => function ($query) use ($request) {
            $query->when($request->loja_id, function ($query, $value) {
                $query->where("loja_id", $value);
            });
        }])
        ->when( $isMateriaPrima,
            fn($q) => $q->where('tipo_stock', 'P'),
            fn($q) => $q->where('tipo_stock', '!=', 'P')
        )
        ->where("entidade_id", $entidade->empresa->id)
        ->orderBy("nome")
        ->get();
        
        // Preparar os dados para a resposta
        $dados = $produtos->map(function ($produto) use ($request) {
        
            $dataInicio = $request->data_inicio ? Carbon::parse($request->data_inicio) : Carbon::now()->startOfDay();
            $dataFinal = $request->data_final ? Carbon::parse($request->data_final) : Carbon::now()->endOfDay();
            
            // totdal vendido
            $quantidadeVendida = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("quantidade");

            $totalVendida = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("valor_pagar");

            $totalCusto = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("custo");

            $totalLucro = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("lucro");

            $totalRetencaoAcumuada = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("retencao_fonte");

            $quantidadeEmEstoque = $produto->converterDaBase($produto->stocks->sum("stock"), $produto->unidade);
                
            $quantidadeRestante = $quantidadeEmEstoque - $quantidadeVendida;

            $quantidadeInicial = $quantidadeEmEstoque + $quantidadeVendida;

            return  (object) [
                "id" => $produto->id,
                "codigo_barra" => $produto->codigo_barra,
                "produto" => $produto->nome,
                "preco" => $produto->preco_venda,
                "custo" => $produto->preco_custo,
                "imposto" => $produto->taxa,
                "tipo" => $produto->tipo,
                "unidade" => $produto->unidade,
                "desconto" => 0,
                "total_liquido_vendido" => $totalVendida,
                "total_liquido_custo" => $totalCusto,
                "total_liquido_lucro" => $totalLucro,
                "total_retencao_acumulada" => $totalRetencaoAcumuada,
                "total_liquido_restante" => $produto->preco_venda * $quantidadeInicial,
                "total_liquido_geral" => $produto->preco_custo * $quantidadeEmEstoque,
                "quantidade_inicial" => $quantidadeInicial,
                "quantidade_vendida" => $quantidadeVendida,
                "quantidade_estoque" => $quantidadeEmEstoque,
                "quantidade_restante" => $quantidadeRestante,
            ];
        })
        // Filtro para trazer apenas produtos com quantidade > 0 quando solicitado
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

        $empresa = Entidade::with(["caixas", "users", "lojas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => $isMateriaPrima ? 'Stock de Matérias-primas' : 'Stock de Produtos',
            "isMateriaPrima" => $isMateriaPrima,
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "vendas" => $dados,
            "entidade" => $entidade,
            "requests" => $request->all("data_inicio", "data_final", "apenas_com_quantidade", "loja_id", "user_id", "tipo_preco"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.dashboard-por-artigos", $head);
    }

    public function vendas_por_artigo_anterior(Request $request, string $tipo = 'produto')
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $isMateriaPrima = $tipo === 'materias-primas';
   
        $produtos = Produto::with(["vendas" => function ($query) use ($request) {
            // Filtrar as vendas com base nas datas fornecidas pelo usuário
            $query->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::parse($value));
            })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate("created_at", "<=", Carbon::parse($value));
                });
            $query->where("status", "!=", "anulada");
        }, "stocks" => function ($query) use ($request) {
            // Filtrar os estoques com base na data final fornecida pelo usuário
            $query->when($request->data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::parse($value));
            })
                ->when($request->data_final, function ($query, $value) {
                    $query->whereDate("created_at", "<=", Carbon::parse($value));
                });
            $query->when($request->loja_id, function ($query, $value) {
                $query->where("loja_id", $value);
            });
        }])
        ->when( $isMateriaPrima,
            fn($q) => $q->where('tipo_stock', 'P'),
            fn($q) => $q->where('tipo_stock', '!=', 'P')
        )
        ->when($request->categoria_id, function ($query, $value) {
            $query->where("categoria_id", $value);
        })
        ->where("entidade_id", $entidade->empresa->id)
        ->orderBy("nome")
        ->get();

        // Preparar os dados para a resposta
        $dados = $produtos->map(function ($produto) use ($request) {
        
            $dataInicio = $request->data_inicio ? Carbon::parse($request->data_inicio) : Carbon::now()->startOfDay();
            $dataFinal = $request->data_final ? Carbon::parse($request->data_final) : Carbon::now()->endOfDay();
            
            // totdal vendido
            $quantidadeVendida = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("quantidade");

            $totalVendida = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("valor_pagar");

            $totalCusto = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("custo");

            $totalLucro = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("lucro");

            $totalRetencaoAcumuada = $produto->vendas
                ->whereBetween("created_at", [$dataInicio, $dataFinal])
                ->sum("retencao_fonte");

            $quantidadeEmEstoque = $produto->converterDaBase($produto->stocks->sum("stock"), $produto->unidade);
                
            $quantidadeRestante = $quantidadeEmEstoque - $quantidadeVendida;

            $quantidadeInicial = $quantidadeEmEstoque + $quantidadeVendida;

            return (object) [
                "id" => $produto->id,
                "codigo_barra" => $produto->codigo_barra,
                "produto" => $produto->nome,
                "preco" => $produto->preco_venda,
                "custo" => $produto->preco_custo,
                "imposto" => $produto->taxa,
                "tipo" => $produto->tipo,
                "unidade" => $produto->unidade,
                "desconto" => 0,
                "total_liquido_vendido" => $totalVendida,
                "total_liquido_custo" => $totalCusto,
                "total_liquido_lucro" => $totalLucro,
                "total_retencao_acumulada" => $totalRetencaoAcumuada,
                "total_liquido_restante" => $produto->preco_venda * $quantidadeInicial,
                "total_liquido_geral" => $produto->preco_custo * $quantidadeEmEstoque,
                "quantidade_inicial" => $quantidadeInicial,
                "quantidade_vendida" => $quantidadeVendida,
                "quantidade_estoque" => $quantidadeEmEstoque,
                "quantidade_restante" => $quantidadeRestante,
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

        $empresa = Entidade::with(["categorias", "caixas", "users", "lojas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => $isMateriaPrima ? 'Stock anterior de Matérias-primas' : 'Stock anterior de Produtos',
            "isMateriaPrima" => $isMateriaPrima,
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "vendas" => $dados,
            "entidade" => $entidade,
            "requests" => $request->all("data_inicio", "data_final", "apenas_com_quantidade", "loja_id", "user_id", "categoria_id"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];
        
        return view("dashboard.vendas.dashboard-por-artigos-anterior", $head);
    }

    // Método auxiliar para calcular o total do carrinho
    private function calcularTotal($carrinho)
    {
        return array_reduce($carrinho, function ($carry, $item) {
            return $carry + $item["valor_pagar"];
        }, 0);
    }

    public function pronto_vendas(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
   
        $pins = Pin::where("entidade_id", $entidade->empresa->id)->get();

        if (empty($pins)) {
            return redirect()->route("pins.create");
        }
        
        $userloja = UserLoja::where('usuario_id', Auth::user()->id)->first();
        
        // recuperar todos os caixas aberto
        $caixas = Caixa::where('status_admin', 'liberado')
            ->where("entidade_id", $entidade->empresa->id)
            ->where("loja_id", $userloja->loja_id ?? "")
            ->get();

        if (empty($caixas)) {
            return redirect()->route("caixa.caixas");
        }

        // Exibe a página do carrinho
        $carrinho = Session::get("carrinho", []);
        $total = $this->calcularTotal($carrinho);

        $total_pagar = NULL;

        $caixaActivo = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where('status_admin', 'liberado')
            ->where("user_open_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
        ->first();

        if ($caixaActivo) {
            $total_pagar = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "CAIXA")
                ->where("caixa_id", $caixaActivo->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
            ->sum("valor_pagar");
        }

        $checkCaixa = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where('status_admin', 'liberado')
            ->where("user_open_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
        ->first();

        $lockStartTime = Session::get("lock_start_time");
        $unlockTime = Carbon::parse($lockStartTime)->addHours(24);
        $remainingTime = $unlockTime->diffInSeconds(Carbon::now());

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");
        
        $receitas = Receita::where('type', 'R')->where('entidade_id', $entidade->empresa->id)->get();
        $dispesas = Receita::where('type', 'D')->where('entidade_id', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();

        $data_actual = date("Y-m-d");

        $operacoes = OperacaoFinanceiro::where('date_at', $data_actual)
            ->whereIn('status_caixa', ['pendente'])
            ->where('entidade_id', $entidade->empresa->id)
            ->where('user_open_id', Auth::user()->id)
            ->whereIn('type', ['R', 'D'])
            ->with(['centro_custo', 'fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'subconta'])
            ->orderBy('created_at', 'desc')
        ->get();
            
        $produtos = Produto::with(["marca", "variacao", "estoque", "estoques"])
            ->whereIn("tipo", ["P", "S"])
            ->whereIn("id", $meus_produtos)
            ->where("entidade_id", $entidade->empresa->id)
        ->get();

        $head = [
            "titulo" => "Pronto Vendas",
            "descricao" => env("APP_NAME"),
            
            "categorias" => Categoria::with("produtos.marca", "produtos.variacao")
                ->where("entidade_id", $entidade->empresa->id)
                ->whereHas("produtos", function ($query) use($meus_produtos) {
                    $query->whereIn("tipo", ["P", "S"])
                    ->whereIn("id", $meus_produtos);
                })
            ->get(),
                
            "clientes" => Cliente::where("entidade_id", $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "total_pagar" => $total_pagar,
            "caixas" => $caixas,
            "checkCaixa" => $checkCaixa,

            "produtos" => $produtos,

            "carrinho" => $carrinho,
            "total" => $total,
            "receitas" => $receitas,
            "dispesas" => $dispesas,
            "fornecedores" => $fornecedores,
            "operacoes" => $operacoes,

            "remainingTime" => $remainingTime,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
            
            "loja"
            
        ];

        return view("dashboard.vendas.index", $head);
    }

    public function pronto_vendas_mesas(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        $pins = Pin::where("entidade_id", $entidade->empresa->id)->get();
        $mesas = Mesa::where("entidade_id", $entidade->empresa->id)->get();

        $checkCaixa = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where('status_admin', 'liberado')
            ->where("user_open_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->first();

        $head = [
            "titulo" => "Pronto Vendas Mesas",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "mesas" => $mesas,
            "pins" => $pins,
            "checkCaixa" => $checkCaixa,
            "clientes" => Cliente::where("entidade_id", $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];
        return view("dashboard.vendas.venda-pedido-mesas", $head);
    }

    public function pronto_vendas_quatros(Request $request)
    {

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $pins = Pin::where("entidade_id", $entidade->empresa->id)->get();
        $quartos = Quarto::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Pronto Vendas Quartos",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "quartos" => $quartos,
            "clientes" => Cliente::where("entidade_id", $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.venda-pedido-quartos", $head);
    }

    public function pronto_vendas_mesas_pedidos(Request $request, $id)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $mesa = Mesa::findOrFail(Crypt::decrypt($id));
        $pins = Pin::where("entidade_id", $entidade->empresa->id)->get();
        if (empty($pins)) {
            return redirect()->route("pins.create");
        }

        // recuperar todos os caixas aberto
        $caixas = Caixa::where('status_admin', 'liberado')->where("entidade_id", $entidade->empresa->id)->get();

        // where("active", false)->where("status", "fechado")->

        if (empty($caixas)) {
            return redirect()->route("caixa.caixas");
        }

        $data = date("Y-m-d");

        $movimento_caixa = Venda::where("user_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->whereDate("created_at", Carbon::parse($data))
            ->select(DB::raw("SUM(valor_total) as total_vendido"))
            ->first();

        $movimentos = NULL;
        $total_pagar = NULL;
        $total_unidades = NULL;
        $total_produtos = NULL;

        $checkCaixa = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where("user_open_id", Auth::user()->id)
            ->where('status_admin', 'liberado')
            ->where("entidade_id", $entidade->empresa->id)
            ->first();

        $caixaActivo = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where("user_open_id", Auth::user()->id)
            ->where('status_admin', 'liberado')
            ->where("entidade_id", $entidade->empresa->id)
            ->first();

        if ($caixaActivo) {
            $movimentos = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "MESA")
                ->where("mesa_id", $mesa->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->where("user_id", Auth::user()->id)
                ->with(["produto"])
                ->get();

            $total_pagar = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "MESA")
                ->where("mesa_id", $mesa->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->where("user_id", Auth::user()->id)
                ->sum("valor_pagar");

            $total_produtos = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "MESA")
                ->where("mesa_id", $mesa->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->where("user_id", Auth::user()->id)
                ->count();

            $total_unidades = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "MESA")
                ->where("mesa_id", $mesa->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->where("user_id", Auth::user()->id)
                ->sum("quantidade");
        } else {
            return redirect()->back()->with("danger", "Nenhum caixa Aberto no momento!");
        }

        if ($mesa->solicitar_ocupacao == "LIVRE") {
            $mesa->solicitar_ocupacao = "OCUPADA";
            $mesa->update();
        }

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");


        $head = [
            "titulo" => "Pronto Vendas Mesas",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "mesa" => $mesa,
            "categorias" => Categoria::with("produtos.marca", "produtos.variacao")->where("entidade_id", $entidade->empresa->id)->get(),
            "produtos" => Produto::with(["marca", "variacao", "estoque"])
                ->where("entidade_id", $entidade->empresa->id)
                ->whereIn("id", $meus_produtos)
                ->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "clientes" => Cliente::where("entidade_id", $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_unidades" => $total_unidades,
            "total_produtos" => $total_produtos,
            "checkCaixa" => $checkCaixa,
            "caixas" => $caixas,
            "movimento_caixa" => $movimento_caixa,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.vendas.index-mesas", $head);
    }

    public function pronto_vendas_mesas_quartos(Request $request, $id)
    {

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $quarto = Quarto::findOrFail(Crypt::decrypt($id));

        $reserva = Reserva::where("code", $quarto->code)
            ->with([
                "quarto",
                "exercicio",
                "periodo",
                "cliente.estado_civil",
                "cliente.seguradora",
                "cliente.provincia",
                "cliente.municipio",
                "cliente.distrito"
            ])->first();

        if (!$reserva) {
            return redirect()->route("pronto-venda-quartos")->with("danger", "Reserva não encontrada!");
        }

        if ($quarto->solicitar_ocupacao == "LIVRE") {
            $quarto->solicitar_ocupacao = "OCUPADA";
            $quarto->update();
        }

        $pins = Pin::where([
            ["entidade_id", $entidade->empresa->id],
        ])->get();

        if (empty($pins)) {
            return redirect()->route("pins.create");
        }

        // recuperar todos os caixas fechados
        $caixas = Caixa::where('status_admin', 'liberado')
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        // where("active", false)->where("status", "fechado")->

        if (empty($caixas)) {
            return redirect()->route("caixa.caixas");
        }

        $data = date("Y-m-d");

        $movimento_caixa = Venda::where("user_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->whereDate("created_at", Carbon::parse($data))
            ->select(DB::raw("SUM(valor_total) as total_vendido"))
            ->first();

        $movimentos = NULL;
        $total_pagar = NULL;
        $total_unidades = NULL;
        $total_produtos = NULL;

        $checkCaixa = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where('status_admin', 'liberado')
            ->where("user_open_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->first();


        $caixaActivo = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where('status_admin', 'liberado')
            ->where("user_open_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->first();

        if ($caixaActivo) {
            $movimentos = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "QUARTO")
                ->where("quarto_id", $quarto->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->with(["produto"])
                ->get();

            $total_pagar = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "QUARTO")
                ->where("quarto_id", $quarto->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->sum("valor_pagar");

            $total_produtos = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "QUARTO")
                ->where("quarto_id", $quarto->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->count();

            $total_unidades = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", "QUARTO")
                ->where("quarto_id", $quarto->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->sum("quantidade");
        } else {
            return redirect()->route("pronto-venda-quartos")->with("danger", "Nenhum caixa Aberto no momento!");
        }


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");




        $head = [
            "titulo" => "Pronto Vendas Quartos",
            "descricao" => env("APP_NAME"),
            "empresa" => $empresa,
            "quarto" => $quarto,
            "categorias" => Categoria::with("produtos.marca", "produtos.variacao")->where([
                ["entidade_id", $entidade->empresa->id]
            ])->get(),
            "produtos" => Produto::with(["marca", "variacao", "estoque"])
                ->where("entidade_id", $entidade->empresa->id)
                ->whereIn("id", $meus_produtos)
                ->get(),
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "clientes" => Cliente::where("entidade_id", $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_unidades" => $total_unidades,
            "total_produtos" => $total_produtos,
            "checkCaixa" => $checkCaixa,
            "caixas" => $caixas,
            "reserva" => $reserva,
            "movimento_caixa" => $movimento_caixa,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];


        return view("dashboard.vendas.index-quartos", $head);
    }

    public function buscar_produto(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        return response()->json([
            "loja" => Entidade::findOrFail($entidade->empresa->id),
            "produtos" => Produto::with(["marca", "variacao", "estoque"])
                ->where("entidade_id", $entidade->empresa->id)
                // ->where("tipo", "P")
                ->whereIn("id", $meus_produtos)
                ->when($request->produto, function ($query, $value) {
                    $query->where("nome", "like", "%{$value}%");
                })
                ->with("marca", "variacao")
                ->get(),
        ], 200);
    }

    public function buscar_produto_codigo_barra(Request $request)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();

            // Comita a transação se tudo estiver correto
            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            $produto = Produto::with(["marca", "variacao", "categoria", "estoque"])
                ->where("codigo_barra", $request->produto_id)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            // quarto
            if (isset($request->quarto) && !empty($request->quarto)) {
                $entid_ = Quarto::findOrFail($request->quarto);
                $campo = "quarto_id";
                $status_uso = "QUARTO";
            }

            // mesa
            if (isset($request->mesa) && !empty($request->mesa)) {
                $entid_ = Mesa::findOrFail($request->mesa);
                $campo = "mesa_id";
                $status_uso = "MESA";
            }

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $loja = Loja::where("entidade_id", $entidade->empresa->id)
            //     ->whereIn("id", $minhas_lojas)
            //     ->where("status", "activo")
            //     ->first();
                
            $loja = $this->LOJA_ACTIVA_USER();


            if (!$loja) {
                return response()->json([
                    "messagem" => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"
                ], 404);
            }

            // verificar quantidade de produto no estoque da loja
            $verificar_quantidade = Estoque::where("loja_id", $loja->id)
                ->where("produto_id", $produto->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->sum("stock");

            $verificar_quantidade = (float) $verificar_quantidade;

            if ($verificar_quantidade <= 0) {
                return response()->json([
                    "messagem" => "A Loja activa não têm este produto em stock para poder comercializar!"
                ], 404);
            }

            if ($produto->estoque) {
                if ($produto->estoque->stock <= $produto->estoque->stock_minimo) {
                    return response()->json([
                        "messagem" => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                    ], 404);
                }
            } else {
                return response()->json([
                    "messagem" => "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento."
                ], 404);
            }

            $caixaActivo = Caixa::where("active", true)
                ->where("status", "aberto")
                ->where('status_admin', 'liberado')
                ->where("user_open_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            // SElecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
            $lote = Lote::where("produto_id", $produto->id)
                ->where("codigo_barra", $produto->codigo_barra)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if ($lote && $lote->status == "expirado" && $lote->data_validade <= date("Y-m-d")) {
                return response()->json(["message" => "O produto: { $produto->nome } parece estar expirado, por isso não é possível finalizar a venda, visando a segurança da população."], 404);
            }

            // verificar se este produto tem quantidade para ser vendidas no lote não expirado, isto por que  não podemos permitir o sistema vender quantidades que não existem
            $verificar_lote_produto = $produto->verificar_lote_produto($produto->id, $lote->id, $entidade->empresa->id);

            if ($verificar_lote_produto <= 0) {
                return response()->json(["message" => "O produto {$produto->nome} não possui quantidade disponível para comercialização, pois o sistema não conseguiu identificar a quantidade do lote referente a este produto. Por favor, verifique se existem lotes expirados. Caso contrário, ative o código de barras correspondente ao lote!"], 400);
            }

            if ($caixaActivo) {

                $caixa_id = NULL;

                Registro::create([
                    "registro" => "Saída de Stock",
                    "data_registro" => date("Y-m-d"),
                    "quantidade" => 1,
                    "tipo" => "S",
                    'status' => 'V',
                    "produto_id" => $produto->id,
                    "observacao" => "Saída do produto {$produto->nome} para venda",
                    "loja_id" => $loja->id,
                    "lote_id" => $lote ? $lote->id : NULL,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                $verificarProdutoAdicionado = ItemVenda::where("status", "processo")
                    ->where("produto_id", $produto->id)
                    ->where("{$campo}", $entid_->id)
                    ->where("status_uso", $status_uso)
                    ->where("entidade_id", $entidade->empresa->id)
                    ->where("user_id", Auth::user()->id)
                    ->first();

                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = $produto->preco_venda * $request->quantidade ?? 1;
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;

                if ($verificarProdutoAdicionado) {
                    $update = ItemVenda::findOrFail($verificarProdutoAdicionado->id);

                    $desconto = ($produto->preco_venda * ($update->quantidade + $request->quantidade ?? 1)) * ($update->desconto_aplicado / 100);

                    $valorBase = $produto->preco_venda * ($update->quantidade + $request->quantidade ?? 1);
                    // calculo do iva
                    $valorIva = ($produto->taxa / 100) * $valorBase;

                    $update->quantidade = $update->quantidade + $request->quantidade ?? 1;
                    $update->valor_pagar = ($valorBase + $valorIva) - $desconto;

                    $update->custo = $produto->preco_custo;
                    $update->lucro = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $desconto;

                    $update->valor_base = $valorBase;
                    $update->valor_iva = $valorIva;

                    $update->update();

                    $produto->estoque->stock = $produto->estoque->stock - $request->quantidade ?? 1;
                    $produto->estoque->update();
                } else {
                    $create = ItemVenda::create(
                        [
                            "produto_id" => $produto->id,
                            "quantidade" => $request->quantidade ?? 1,
                            'quantidade_devolvida' => 0,
                            "user_id" => Auth::user()->id,
                            "valor_pagar" => $valorBase + $valorIva,
                            "preco_unitario" => $produto->preco_venda,
                            "custo" => $produto->preco_custo * $request->quantidade ?? 1,
                            "lucro" => ($produto->preco_venda - $produto->preco_custo) * $request->quantidade ?? 1,
                            "desconto_aplicado" => 0,
                            "status" => "processo",
                            "valor_base" => $valorBase,
                            "valor_iva" => $valorIva,
                            "desconto_aplicado_valor" => 0,
                            "iva" => $produto->imposto,
                            "iva_taxa" => $produto->taxa,
                            "texto_opcional" => "",
                            "status_uso" => $status_uso,
                            "caixa_id" => $caixa_id,
                            "{$campo}" => $entid_->id,
                            "code" => NULL,
                            "numero_serie" => "",
                            "entidade_id" => $entidade->empresa->id,
                        ]
                    );

                    if ($create->save()) {
                        $produto->estoque->stock = $produto->estoque->stock - $request->quantidade;
                        $produto->estoque->update();
                    } else {
                        return response()->json([
                            "messagem" => "O correu um erro ão tentar adicionar este produto!"
                        ], 404);
                    }
                }
            } else {
                return response()->json([
                    "messagem" => "Verifica se tens um caixa aberto, por favor!"
                ], 404);
            }

            $movimentos = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", $status_uso)
                ->where("{$campo}", $entid_->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->with(["produto"])
                ->get();

            $total_pagar = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", $status_uso)
                ->where("{$campo}", $entid_->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->sum("valor_pagar");

            $total_produtos = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", $status_uso)
                ->where("{$campo}", $entid_->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->count();

            $total_unidades = ItemVenda::where("code", NULL)
                ->where("status", "processo")
                ->where("status_uso", $status_uso)
                ->where("{$campo}", $entid_->id)
                ->where("user_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->sum("quantidade");


            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger("Error", $e->getMessage());
            return redirect()->back()->with("danger", $e->getMessage());
        }

        return response()->json([
            "movimentos" => $movimentos,
            "total_pagar" => $total_pagar,
            "total_produtos" => $total_produtos,
            "total_unidades" => $total_unidades,
        ], 200);

        // $produt = Produto::where("codigo_barra", $request->produto_codigo_barra)->first();

        // if($produt && isset($request->mesa) && !empty($request->mesa)){


        // }
        // if($produt){
        // 	return redirect()->route("adicionar-produto", $produt->id);
        // }
        // // carrinho.adicionar-mesa
        // return redirect()->back();
    }

    public function actualizar_vendas($id, $back)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        $movimento = ItemVenda::with("produto")->findOrFail($id);

        $produto = Produto::findOrFail($movimento->produto_id);
        $grupo_precos = ProdutoGrupoPreco::with(["produto"])->where("produto_id", $produto->id)->get();


        if ($back) {
            $mesa = Mesa::find($back);
        }

        $head = [
            "titulo" => env("APP_NAME") . " Pronto Vendas",
            "descricao" => env("APP_NAME"),
            "categorias" => Categoria::with("produtos.marca", "produtos.variacao")->where([
                ["entidade_id", $entidade->empresa->id]
            ])->get(),
            "dados" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,

            "produto" => $produto,
            "grupo_precos" => $grupo_precos,
            "mesa" => $mesa,

            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];


        return view("dashboard.vendas.actualizar-quantidade", $head);
    }

    public function actualizar_vendas_update(Request $request, $id, $back = null)
    {

        try {
            // Inicia a transação
            DB::beginTransaction();

            $movimento = ItemVenda::with("produto")->findOrFail($id);
            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $loja = Loja::where("entidade_id", $entidade->empresa->id)
            //     ->whereIn("id", $minhas_lojas)
            //     ->where("status", "activo")
            //     ->first();
                
            $loja = $this->LOJA_ACTIVA_USER();

            if (!$loja) {
                Alert::warning("Atenção", "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!");
                return redirect()->back()->with("warning", "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto.");
            }

            // verificar quantidade de produto no estoque da loja
            $verificar_quantidade = Estoque::where("loja_id", $loja->id)
                ->where("produto_id", $movimento->produto_id)
                ->where("entidade_id", $entidade->empresa->id)
                ->sum("stock");

            $verificar_quantidade = (float) $verificar_quantidade;

            if ($request->quantidade > $verificar_quantidade) {
                Alert::warning("Atenção", "A Loja activa não têm esta quantidade de produto em stock para poder comercializar!");
                return redirect()->back()->with("warning", "A Loja activa não têm esta quantidade de produto em stock para poder comercializar!");
            }

            if ($movimento) {

                $produto = Produto::with("estoque")->findOrFail($movimento->produto_id);

                if ($request->quantidade > $produto->estoque->stock) {
                    Alert::warning("Atenção", "A quantidade Adiciona nesta compra e maior do que a existente no Stock!");
                    return redirect()->back();
                }

                $desconto = ($produto->preco_venda * $request->quantidade) * ($request->desconto_aplicado / 100);

                $produto->estoque->stock = ($produto->estoque->stock + $movimento->quantidade) - $request->quantidade;

                $valorBase = $produto->preco_venda * $request->quantidade;
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;

                $movimento->quantidade = $request->quantidade;
                $movimento->valor_pagar = ($valorBase + $valorIva) - $desconto;
                $movimento->preco_unitario = $produto->preco_venda;


                $movimento->custo = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;


                $movimento->valor_base = $valorBase;
                $movimento->valor_iva = $valorIva;

                $movimento->desconto_aplicado = $request->desconto_aplicado;
                $movimento->desconto_aplicado_valor = $desconto;

                $movimento->iva = $request->iva;
                $movimento->texto_opcional = $request->texto_opcional;
                $movimento->numero_serie = $request->numero_serie;
                if ($movimento->update()) {

                    $produto->estoque->update();
                } else {
                    Alert::error("Erro", "Ao tentar actualizar os dodos deste produto nesta venda");
                    if ($back == "factura") {
                        return redirect()->route("facturas.create");
                    } else if (Mesa::find($back)) {
                        $mesa = Mesa::find($back);
                        return redirect()->route("pronto-venda-mesas-pedidos", Crypt::encrypt($mesa->id));
                    } else {
                        return redirect()->route("pronto-venda");
                    }
                }


                if ($request->quantidade > $request->quantidade_anterior) {

                    Registro::create([
                        "registro" => "Saída de Stock",
                        "data_registro" => date("Y-m-d"),
                        "tipo" => "S",
                        'status' => 'V',
                        "quantidade" => (float) $request->quantidade - (float) $request->quantidade_anterior,
                        "produto_id" => $produto->id,
                        "observacao" => "Saída de produto {$produto->nome} para venda",
                        "loja_id" => $loja->id,
                        "lote_id" => NULL,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                } else if ($request->quantidade < $request->quantidade_anterior) {

                    $quantidade = (float) $request->quantidade_anterior - (float) $request->quantidade;

                    Registro::create([
                        "registro" => "Saída de Stock",
                        "tipo" => "S",
                        'status' => 'V',
                        "data_registro" => date("Y-m-d"),
                        "quantidade" => (float) $request->quantidade_anterior - (float) $request->quantidade,
                        "produto_id" => $produto->id,
                        "observacao" => "Saída de produto {$produto->nome} para venda",
                        "loja_id" => $loja->id,
                        "lote_id" => NULL,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                }
            }

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger("Error", $e->getMessage());
            return redirect()->back()->with("danger", $e->getMessage());
            // return Response()->json($e->getMessage());
            // Trate o erro ou exiba uma mensagem de falha
            // por exemplo: return response()->json(["message" => "Erro ao salvar"], 500);
        }


        if ($back == "factura") {
            return redirect()->route("facturas.create");
        } else if (Mesa::find($back)) {
            $mesa = Mesa::find($back);
            return redirect()->route("pronto-venda-mesas-pedidos", Crypt::encrypt($mesa->id));
        } else {
            return redirect()->route("pronto-venda");
        }
    }

    // adicionar produto ao carrinho
    public function adicionar_produto($id, $mesa_caixa = "")
    {
        try {
            // Inicia a transação
            DB::beginTransaction();
            // Comita a transação se tudo estiver correto

            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
            $produto = Produto::with("marca", "variacao", "categoria", "estoque")->findOrFail($id);


            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $loja = Loja::where("entidade_id", $entidade->empresa->id)
            //     ->whereIn("id", $minhas_lojas)
            //     ->where("status", "activo")
            //     ->first();
                
            $loja = $this->LOJA_ACTIVA_USER();


            if (!$loja) {
                Alert::warning("Atenção", "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!");
                return redirect()->back()->with("warning", "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto.");
            }

            // verificar quantidade de produto no estoque da loja
            $verificar_quantidade = Estoque::where("loja_id", $loja->id)
                ->where("produto_id", $produto->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->sum("stock");

            $verificar_quantidade = (float) $verificar_quantidade;

            if ($verificar_quantidade <= 0) {
                Alert::warning("Atenção", "A Loja activa não têm este produto em stock para poder comercializar!");
                return redirect()->back()->with("warning", "A Loja activa não têm este produto em stock para poder comercializar!");
            }

            if ($produto->estoque) {
                if ($produto->estoque->stock <= $produto->estoque->stock_minimo) {
                    Alert::warning("Atenção", "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento.");
                    return redirect()->back();
                }
            } else {
                Alert::warning("Atenção", "A quantidade deste produto em estoque está abaixo do limite crítico, impedindo a venda no momento.");
                return redirect()->back();
            }

            $caixaActivo = Caixa::where("active", true)
                ->where("status", "aberto")
                ->where('status_admin', 'liberado')
                ->where("user_open_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if (!empty($caixaActivo)) {


                Registro::create([
                    "registro" => "Saída de Stock",
                    "tipo" => "S",
                    'status' => 'V',
                    "data_registro" => date("Y-m-d"),
                    "quantidade" => 1,
                    "produto_id" => $produto->id,
                    "observacao" => "Saída do produto {$produto->nome} para venda",
                    "loja_id" => $loja->id,
                    "lote_id" => NULL,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);


                if ($mesa_caixa == "") {
                    $status_uso = "CAIXA";
                    $mesa_id = NULL;
                    $caixa_id = $caixaActivo->id;
                } else {
                    $mesa_id = $mesa_caixa;
                    $status_uso = "MESA";
                    $caixa_id = NULL;
                }

                if ($status_uso == "CAIXA") {

                    $verificarProdutoAdicionado = ItemVenda::where([
                        ["status", "processo"],
                        ["produto_id", $produto->id],
                        ["caixa_id", $caixa_id],
                        ["entidade_id", $entidade->empresa->id],
                        ["entidade_id", $entidade->empresa->id],
                        ["user_id", Auth::user()->id],
                    ])->first();
                }

                if ($status_uso == "MESA") {

                    $verificarProdutoAdicionado = ItemVenda::where([
                        ["status", "processo"],
                        ["produto_id", $produto->id],
                        ["mesa_id", $mesa_id],
                        ["entidade_id", $entidade->empresa->id],
                        ["user_id", Auth::user()->id],
                    ])->first();
                }

                // calcudo do total de incidencia
                //________________ valor total _____________
                $valorBase = $produto->preco_venda * 1;
                // calculo do iva
                $valorIva = ($produto->taxa / 100) * $valorBase;

                if ($verificarProdutoAdicionado) {
                    $update = ItemVenda::findOrFail($verificarProdutoAdicionado->id);

                    $desconto = ($produto->preco_venda * ($update->quantidade + 1)) * ($update->desconto_aplicado / 100);

                    $valorBase = $produto->preco_venda * ($update->quantidade + 1);
                    // calculo do iva
                    $valorIva = ($produto->taxa / 100) * $valorBase;

                    $update->quantidade = $update->quantidade + 1;
                    $update->valor_pagar = ($valorBase + $valorIva) - $desconto;

                    $update->custo = $produto->preco_custo * $update->quantidade;
                    $update->lucro = ($produto->preco_venda - $produto->preco_custo) * $update->quantidade;

                    $update->desconto_aplicado = $update->desconto_aplicado;
                    $update->desconto_aplicado_valor = $desconto;

                    $update->valor_base = $valorBase;
                    $update->valor_iva = $valorIva;

                    $update->update();

                    $produto->estoque->stock = $produto->estoque->stock - 1;
                    $produto->estoque->update();

                    // return redirect()->back();
                    // return redirect()->route("pronto-venda");
                } else {
                    $create = ItemVenda::create([
                        "produto_id" => $produto->id,
                        "quantidade" => 1,
                        'quantidade_devolvida' => 0,
                        "user_id" => Auth::user()->id,
                        "valor_pagar" => $valorBase + $valorIva,
                        "preco_unitario" => $produto->preco_venda,
                        "custo" => $produto->preco_custo,
                        "lucro" => ($produto->preco_venda - $produto->preco_custo) * 1,
                        "desconto_aplicado" => 0,
                        "status" => "processo",
                        "valor_base" => $valorBase,
                        "valor_iva" => $valorIva,
                        "desconto_aplicado_valor" => 0,
                        "iva" => $produto->imposto,
                        "iva_taxa" => $produto->taxa,
                        "texto_opcional" => "",
                        "status_uso" => $status_uso,
                        "caixa_id" => $caixa_id,
                        "mesa_id" => $mesa_id,
                        "code" => NULL,
                        "numero_serie" => "",
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    if ($create->save()) {
                        $produto->estoque->stock = $produto->estoque->stock - 1;
                        $produto->estoque->update();

                        // return redirect()->route("pronto-venda");
                        // return redirect()->back();
                    } else {
                        Alert::error("Erro", "O correu um erro ão tentar adicionar este produto");
                        // return redirect()->route("pronto-venda");
                        return redirect()->back();
                    }
                }
            } else {
                Alert::error("Erro", "Verifica se tens um caixa aberto, por favor!");
                return redirect()->back();
            }

            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger("Error", $e->getMessage());
            return redirect()->back()->with("danger", $e->getMessage());
        }

        return redirect()->back();
    }

    // adicionar produto ao carrinho
    public function remover_produto($id, $back = null)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();

            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // $loja = Loja::where("entidade_id", $entidade->empresa->id)
            //     ->whereIn("id", $minhas_lojas)
            //     ->where("status", "activo")
            //     ->first();
            
            $loja = $this->LOJA_ACTIVA_USER();

            if (!$loja) {
                Alert::warning("Atenção", "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!");
                return redirect()->back()->with("warning", "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto.");
            }

            $movimento = ItemVenda::findOrFail($id);
            $movimento->delete();

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger("Error", $e->getMessage());
            return redirect()->back()->with("danger", $e->getMessage());
        }

        if ($back == "factura") {
            return redirect()->route("facturas.create");
        } else if (Mesa::find($back)) {
            $mesa = Mesa::find($back);
            return redirect()->route("pronto-venda-mesas-pedidos", Crypt::encrypt($mesa->id));
        } else {
            return redirect()->route("pronto-venda");
        }
    }

    public function finalizar_vendas_create(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui


            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

            $cliente = Cliente::findOrFail($request->cliente_id);
            $receita = Receita::where("type", "R")->where("entidade_id", $entidade->empresa->id)->first();
            $subconta_cliente = Subconta::where("code", $cliente->code)->first();

            $formaPagamento = TipoPagamento::where("tipo", $request->pagamento)->first();

            $code = uniqid(time());

            $valor_multicaixa = 0;
            $valor_cash = 0;

            // verificar se selecionou um produto ou não para realizar a venda
            $movimento = ItemVenda::where("user_id", Auth::user()->id)
                ->where("code", NULL)
                ->where("status_uso", $request->venda_realizado)
                ->where("status", "processo")
                ->where("entidade_id", $entidade->empresa->id)
                ->with(["produto"])
                ->get();

            if (count($movimento) == 0) {
                return response()->json(["message" => "O correu um erro, não existe nenhum produto selecionado!"], 400);
            }

            $caixaActivo = Caixa::where("active", true)
                ->where("status", "aberto")
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            $bancoActivo = ContaBancaria::where("active", true)
                ->where("status", "aberto")
                ->where("user_open_id", Auth::user()->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if (!$caixaActivo) {
                Alert::error("Erro", "Por favor, não podes realizar nenhuma venda sem antes abrir o caixa!");
                return redirect()->back();
            }

            $contador_facturas = Venda::where("factura", $request->documento)->where("ano_factura", $entidade->empresa->ano_factura)->where("entidade_id", $entidade->empresa->id)->count();

            $numeroFacturaDoc = $contador_facturas + 1;

            $designacao_factura = "{$request->documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFacturaDoc}";

            $request->total_pagar = (float) $request->total_pagar;

            if ($formaPagamento->tipo == "NU") {

                $valor_cash = (float) $request->total_pagar;
                $valor_multicaixa = 0;
                $request->valor_entregue = (float) $request->valor_entregue;
                $banco_id = NULL;

                $subconta_caixa = Subconta::where("code", $caixaActivo->code)->first();

                OperacaoFinanceiro::create([
                    "nome" => $designacao_factura,
                    "status" => "pago",
                    "formas" => "C",
                    "motante" => $request->total_pagar,
                    "subconta_id" => $subconta_caixa->id,
                    "cliente_id" => $cliente->id,
                    "model_id" => $receita ? $receita->id : NULL,
                    "type" => "R",
                    "parcelado" => "N",
                    "status_pagamento" => "pago",
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    "data_recebimento" => date("Y-m-d"),
                    "forma_recebimento_id" => $formaPagamento->id,
                    "code" => $code,
                    "descricao" => "VENDA REALIZADA COM SUCESSO",
                    "movimento" => "E",
                    "date_at" => date("Y-m-d"),
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                ## vamor aumentar o valor do caixa - 45/43
                Movimento::create([
                    "user_id" => Auth::user()->id,
                    "subconta_id" => $subconta_caixa->id,
                    "status" => true,
                    "movimento" => "E",
                    "credito" => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    "debito" => $request->total_pagar ?? 0,
                    "observacao" => $request->observacao,
                    "code" => $code,
                    "data_at" => date("Y-m-d"),
                    "entidade_id" => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            } else if ($formaPagamento->tipo == "MB" || $formaPagamento->tipo == "TB" || $formaPagamento->tipo == "DE") {

                if (!$bancoActivo) {
                    return response()->json(["message" => "TPA não activo ou seja não existe nenhum Conta Bancaria activo, verifica e activa uma conta bancária para poder realizar uma venda via TPA.!"], 404);
                }

                $valor_cash = 0;
                $valor_multicaixa =  (float) $request->total_pagar;
                $request->valor_entregue = (float) $request->valor_entregue_multicaixa;
                $banco_id = $caixaActivo->id;

                $subconta_banco = Subconta::where("code", $bancoActivo->code)->first();
                if ($subconta_banco) {
                    OperacaoFinanceiro::create([
                        "nome" => $designacao_factura,
                        "status" => "pago",
                        "formas" => "B",
                        "motante" => $request->total_pagar,
                        "subconta_id" => $subconta_banco->id,
                        "cliente_id" => $cliente->id,
                        "model_id" => $receita ? $receita->id : NULL,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        "type" => "R",
                        "parcelado" => "N",
                        "status_pagamento" => "pago",
                        "data_recebimento" => date("Y-m-d"),
                        "forma_recebimento_id" => $formaPagamento->id,
                        "code" => $code,
                        "descricao" => "VENDA REALIZADA COM SUCESSO",
                        "movimento" => "E",
                        "date_at" => date("Y-m-d"),
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                Movimento::create([
                    "user_id" => Auth::user()->id,
                    "subconta_id" => $subconta_banco->id,
                    "status" => true,
                    "movimento" => "E",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    "credito" => 0,
                    "debito" => $request->total_pagar ?? 0,
                    "observacao" => $request->observacao,
                    "code" => $code,
                    "data_at" => date("Y-m-d"),
                    "entidade_id" => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            } else if ($formaPagamento->tipo == "OU") {

                $valor_cash =  (float)  $request->valor_entregue_input;
                $valor_multicaixa = (float)  $request->valor_entregue_multicaixa_input;
                $request->valor_entregue = (float) $request->valor_entregue_multicaixa_input + (float) $request->valor_entregue_input;
                $banco_id = $caixaActivo->id;

                if (!$bancoActivo) {
                    return response()->json(["message" => "TPA não activo ou seja não existe nenhum Conta Bancaria activo, verifica e activa uma conta bancária para poder realizar uma venda via TPA.!"], 404);
                }

                $subconta_caixa = Subconta::where("code", $caixaActivo->code)->first();
                $subconta_banco = Subconta::where("code", $bancoActivo->code)->first();

                if ($subconta_caixa) {
                    OperacaoFinanceiro::create([
                        "nome" => $designacao_factura,
                        "status" => "pago",
                        "formas" => "C",
                        "motante" => $request->valor_entregue_input,
                        "subconta_id" => $subconta_caixa->id,
                        "cliente_id" => $cliente->id,
                        "model_id" => $receita ? $receita->id : NULL,
                        "type" => "R",
                        "parcelado" => "N",
                        "status_pagamento" => "pago",
                        "data_recebimento" => date("Y-m-d"),
                        "forma_recebimento_id" => $formaPagamento->id,
                        "code" => $code,
                        "descricao" => "VENDA REALIZADA COM SUCESSO",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        "movimento" => "E",
                        "date_at" => date("Y-m-d"),
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                if ($subconta_banco) {
                    OperacaoFinanceiro::create([
                        "nome" => $designacao_factura,
                        "status" => "pago",
                        "formas" => "B",
                        "motante" => $request->valor_entregue_multicaixa_input,
                        "subconta_id" => $subconta_banco->id,
                        "cliente_id" => $cliente->id,
                        "model_id" => $receita ? $receita->id : NULL,
                        "type" => "R",
                        "parcelado" => "N",
                        "status_pagamento" => "pago",
                        "data_recebimento" => date("Y-m-d"),
                        "forma_recebimento_id" => $formaPagamento->id,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        "code" => $code,
                        "descricao" => "VENDA REALIZADA COM SUCESSO",
                        "movimento" => "E",
                        "date_at" => date("Y-m-d"),
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                Movimento::create([
                    "user_id" => Auth::user()->id,
                    "subconta_id" => $subconta_caixa->id,
                    "status" => true,
                    "movimento" => "E",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    "credito" => 0,
                    "debito" => $request->valor_entregue_input ?? 0,
                    "observacao" => $request->observacao,
                    "code" => $code,
                    "data_at" => date("Y-m-d"),
                    "entidade_id" => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                Movimento::create([
                    "user_id" => Auth::user()->id,
                    "subconta_id" => $subconta_banco->id,
                    "status" => true,
                    "movimento" => "E",
                    "credito" => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    "debito" => $request->valor_entregue_multicaixa_input ?? 0,
                    "observacao" => $request->observacao,
                    "code" => $code,
                    "data_at" => date("Y-m-d"),
                    "entidade_id" => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
            }

            if ($request->valor_entregue < $request->total_pagar) {
                return response()->json(["message" => "O Valor Entregue para esta Compra é insuficiente!"], 400);
            }

            $contarFactura = Venda::where("factura", $request->documento)
                ->where("ano_factura", $entidade->empresa->ano_factura)
                ->where("entidade_id", $entidade->empresa->id)
                ->count();

            $ultimoRecibo = Venda::where("factura", $request->documento)
                ->where("ano_factura", $entidade->empresa->ano_factura)
                ->where("entidade_id", $entidade->empresa->id)
                ->orderBy("id", "DESC")
                ->limit(1)
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

            //Manipulação de datas: data actual
            $datactual = Carbon::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));

            $numeroFactura = $contarFactura + 1;

            $rsa = new RSA(); //Algoritimo RSA

            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();

            // Lendo a private key
            $rsa->loadKey($privatekey);

            /**
             * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
             * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */

            $plaintext = $datactual->format("Y-m-d") . ";" . str_replace(" ", "T", $datactual) . ";" . "{$request->documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}" . ";" . number_format($request->total_pagar, 2, ".", "") . ";" . $hashAnterior;

            // HASH
            $hash = "sha1"; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)

            // Lendo a public key
            $rsa->loadKey($publickey);

            $valor_extenso = $this->valor_por_extenso($request->total_pagar);

            if ($request->venda_realizado == "CAIXA") {
                $mesa = Caixa::find($caixaActivo->id);
                $caixa_id = $mesa->id;
                $mesa_id = NULL;
                $quarto_id = NULL;
            }

            if ($request->venda_realizado == "MESA") {
                $mesa = Mesa::find($request->mesa_id);
                $caixa_id = NULL;
                $mesa_id = $mesa->id;
                $quarto_id = NULL;
            }

            if ($request->venda_realizado == "QUARTO") {
                $mesa = Quarto::find($request->quarto_id);
                $caixa_id = NULL;
                $mesa_id = NULL;
                $quarto_id = $mesa->id;
            }

            $lucro_total = 0;
            $custo_total = 0;
            if ($movimento) {
                foreach ($movimento as $movim) {
                    $lucro_total += $movim->lucro;
                    $custo_total += $movim->custo;
                }
            }


            $create = Venda::create([
                "codigo_factura" =>  $numeroFactura,
                "status" => true,
                "cliente_id" => $cliente->id,
                "banco_id" => $banco_id,
                "mesa_id" => $mesa_id,
                "quarto_id" => $quarto_id,
                "mesa_caixa" => $request->venda_realizado,
                "status_factura" => "pago",
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                "status_venda" => "realizado",
                "user_id" => Auth::user()->id,
                "caixa_id" => $caixaActivo->id,
                "valor_entregue" => $request->valor_entregue,
                "valor_total" => $request->total_pagar,
                "lucro_total" => $lucro_total,
                "custo_total" => $custo_total,
                "valor_troco" => $request->valor_entregue - $request->total_pagar,
                "code" => $code,
                "ano_factura" => $entidade->empresa->ano_factura,
                "nome_cliente" => $request->nome_cliente ?? $cliente->nome,
                "documento_nif" => $request->documento_nif ?? $cliente->nif,
                "desconto" => 0,
                "desconto_percentagem" => 0,
                "entidade_id" => $entidade->empresa->id,
                "prazo" => 0,
                "data_emissao" => date("y-m-d"),
                "data_documento" => $datactual,
                "data_vencimento" => date("y-m-d"),
                "data_disponivel" => date("y-m-d"),
                "pagamento" => $formaPagamento->tipo,
                "factura" => $request->documento,
                "factura_next" => "{$request->documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}",
                "observacao" => "venda realizada com sucesso!",
                "referencia" => "venda realizada com sucesso!",

                "retificado" => "N",
                "convertido_factura" => "N",
                "factura_divida" => "N",
                "anulado" => "N",

                "moeda" => $entidade->empresa->moeda ?? "AKZ",
                "valor_extenso" => $valor_extenso,
                "valor_cash" => $valor_cash,
                "valor_multicaixa" => $valor_multicaixa,
                "texto_hash" => $plaintext,
                "hash" => base64_encode($signaturePlaintext),
                "nif_cliente" => $request->documento_nif ?? $cliente->nif,
            ]);

            if ($request->venda_realizado == "CAIXA") {
                $movimentos = ItemVenda::where("user_id", Auth::user()->id)
                    ->where("status", "processo")
                    ->where("caixa_id", $mesa->id)
                    ->where("status_uso", "CAIXA")
                    ->where("entidade_id", $entidade->empresa->id)
                    ->where("code", NULL)
                    ->get();
            }
            if ($request->venda_realizado == "MESA") {
                $movimentos = ItemVenda::where("user_id", Auth::user()->id)
                    ->where("mesa_id", $mesa->id)
                    ->where("status_uso", "MESA")
                    ->where("status", "processo")
                    ->where("entidade_id", $entidade->empresa->id)
                    ->where("code", NULL)
                    ->get();
            }
            if ($request->venda_realizado == "QUARTO") {
                $movimentos = ItemVenda::where("user_id", Auth::user()->id)
                    ->where("quarto_id", $mesa->id)
                    ->where("status_uso", "QUARTO")
                    ->where("status", "processo")
                    ->where("entidade_id", $entidade->empresa->id)
                    ->where("code", NULL)
                    ->get();
            }

            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0;


            if (($entidade->empresa->tipo_entidade->sigla == 'HOTL' || $entidade->empresa->tipo_entidade->sigla == 'REST') || $entidade->empresa->tipo_entidade->sigla == 'RESO') {
                // GESTÃO DE PEDIDOS A CUZINHA
                $inicioDoDia = Carbon::parse($datactual)->startOfDay();
                $fimDoDia = Carbon::parse($datactual)->endOfDay();

                if ($entidade->empresa->destino_pedidos == "Cuzinha") {

                    $total_pedidos = PedidoCuzinha::whereBetween("created_at", [$inicioDoDia, $fimDoDia])->where("entidade_id", $entidade->empresa->id)->count();
                    $total_pedidos = $total_pedidos  + 1;

                    $pedido = PedidoCuzinha::create([
                        'numero' => $total_pedidos,
                        'status' => 'A preparar',
                        'factura_id' => $create->id,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);

                    if ($movimentos) {
                        foreach ($movimentos as $value) {
                            ItemPedidoCuzinha::create([
                                'pedido_id' => $pedido->id,
                                'produto_id' => $value->produto_id,
                                'quantidade' => $value->quantidade,
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                            ]);
                        }
                    }
                }

                $total_pedidos = Venda::whereBetween("created_at", [$inicioDoDia, $fimDoDia])->where("entidade_id", $entidade->empresa->id)->count();
                $total_pedidos = $total_pedidos  + 1;

                $create->numero_pedido_diario = $total_pedidos;
                $create->save();
            }

            if ($movimentos) {
                foreach ($movimentos as $value) {
                    $update = ItemVenda::findOrFail($value->id);
                    $update->code = $code;
                    $update->status = "realizado";
                    $update->factura_id = $create->id;
                    $update->banco_id = $banco_id;
                    $update->update();

                    $totalValorBase += $value->valor_base;
                    $totalValorIva += $value->valor_iva;
                    $totalItems += $value->quantidade;
                }
            }

            $create->total_iva = $totalValorIva;
            $create->total_incidencia = $totalValorBase;
            $create->quantidade = $totalItems;
            $create->save();

            if ($request->venda_realizado == "MESA") {
                $mesa->solicitar_ocupacao = "LIVRE";
                $mesa->update();
            }

            /*if ($request->venda_realizado == "QUARTO") {
                $mesa->code = NULL;
                $mesa->solicitar_ocupacao = "LIVRE";
                $mesa->update();
            }*/

            $vendas = Venda::with("cliente")->where("code", $create->code)->first();
            $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
            $items = ItemVenda::with("produto")->where("code", $vendas->code)->get();

            $factura = Venda::with("cliente")
                ->with(["caixa", "user"])
                ->where("code", $vendas->code)
                ->first();

            $movimentos = ItemVenda::with("produto.motivo")
                ->where("code", $factura->code)
                ->where("entidade_id", $entidade->empresa->id)
                ->get();

            if ($movimentos) {
                $total_incidencia_ise = 0;
                $total_iva_ise = 0;

                $total_incidencia_nor = 0;
                $total_iva_nor = 0;

                $total_incidencia_out = 0;
                $total_iva_out = 0;

                $motivo = "";

                foreach ($movimentos as $item) {
                    if ($item->iva == "NOR") {
                        $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                        $total_iva_nor = $total_iva_nor + $item->valor_iva;
                    }
                    if ($item->iva == "ISE") {
                        $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                        $total_iva_ise = $total_iva_ise + $item->valor_iva;

                        $motivo = $item->produto->motivo->descricao;
                    }
                    if ($item->iva == "OUT") {
                        $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                        $total_iva_out = $total_iva_out + $item->valor_iva;
                    }
                }
            }

            // START CONTABILIDADE
            $subconta_venda_mercadoria = Subconta::where("numero", ENV("VENDA_DE_MERCADORIA"))->first();
            $subconta_custo_mercadoria = Subconta::where("numero", ENV("CUSTO_MERCADORIA_VENDIDA"))->first();
            $subconta_iva = Subconta::where("numero", ENV("IVA_LIQUIDADO"))->first();

            foreach ($movimentos as $car) {

                $produt = Produto::findOrFail($car->produto_id);

                ## creditar na conta proveito - 61/62/63/65 - ou seja diminuir o valor sem o iva
                if ($produt->tipo == "P") {
                    $movimeto = Movimento::create([
                        "user_id" => Auth::user()->id,
                        "subconta_id" => $subconta_venda_mercadoria->id,
                        "status" => true,
                        "movimento" => "S",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        "credito" => $car->valor_pagar ?? 0,
                        "debito" => 0,
                        "observacao" => $request->observacao,
                        "code" => $code,
                        "data_at" => date("Y-m-d"),
                        "entidade_id" => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                if ($produt->tipo == "S") {
                    $movimeto = Movimento::create([
                        "user_id" => Auth::user()->id,
                        "subconta_id" => $produt->subconta_id,
                        "status" => true,
                        "movimento" => "S",
                        "credito" => $car->valor_pagar ?? 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        "debito" => 0,
                        "observacao" => $request->observacao,
                        "code" => $code,
                        "data_at" => date("Y-m-d"),
                        "entidade_id" => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                if ($entidade->empresa->tipo_inventario == "PERMANENTE") {
                    ## creditar na conta proveito - 26 - ou seja diminuir o valor sem o iva
                    $movimeto = Movimento::create([
                        "user_id" => Auth::user()->id,
                        "subconta_id" => $subconta_venda_mercadoria->id,
                        "status" => true,
                        "movimento" => "S",
                        "credito" => ($produt->preco_custo ?? 0) * $car->quantidade,
                        "debito" => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        "observacao" => $request->observacao,
                        "code" => $code,
                        "data_at" => date("Y-m-d"),
                        "entidade_id" => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);

                    ## custo da mercadoria
                    $movimeto = Movimento::create([
                        "user_id" => Auth::user()->id,
                        "subconta_id" => $subconta_custo_mercadoria->id,
                        "status" => true,
                        "movimento" => "E",
                        "credito" => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                        "debito" => ($produt->preco_custo ?? 0) * $car->quantidade,
                        "observacao" => $request->observacao,
                        "code" => $code,
                        "data_at" => date("Y-m-d"),
                        "entidade_id" => $entidade->empresa->id,
                        'exercicio_id' => $this->exercicio(),
                        'periodo_id' => $this->periodo(),
                    ]);
                }

                ## creditar e debitar na conta 31 ou seja preciso aumentar a divida do clientes e depois liquidar da mesma divida
                ## START
                $movimeto = Movimento::create([
                    "user_id" => Auth::user()->id,
                    "subconta_id" => $subconta_cliente->id,
                    "status" => true,
                    "movimento" => "E",
                    "credito" => 0,
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    "debito" => $car->valor_pagar ?? 0,
                    "observacao" => $request->observacao,
                    "code" => $code,
                    "data_at" => date("Y-m-d"),
                    "entidade_id" => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);

                $movimeto = Movimento::create([
                    "user_id" => Auth::user()->id,
                    "subconta_id" => $subconta_cliente->id,
                    "status" => true,
                    "movimento" => "S",
                        'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    "credito" => $car->valor_pagar ?? 0,
                    "debito" => 0,
                    "observacao" => $request->observacao,
                    "code" => $code,
                    "data_at" => date("Y-m-d"),
                    "entidade_id" => $entidade->empresa->id,
                    'exercicio_id' => $this->exercicio(),
                    'periodo_id' => $this->periodo(),
                ]);
                ## - END
            }

            if (isset($request->checkinBox) && !empty($request->checkinBox) && $request->checkinBox == "on") {
                $cartao = ContaCliente::where('cliente_id', $cliente->id)->firstOrFail();

                if ($request->total_pagar > $cartao->saldo) {
                    return response()->json(["message" => "Operação negada: o saldo do cartão de consumo do cliente é insuficiente."], 400);
                }

                $cartao->saldo -= $request->total_pagar;
                $cartao->save();

                MovimentoContaCliente::create([
                    "observacao" => "credito",
                    "documento" => "credito",
                    "conta_id" => $cartao->id,
                    "montante" => $request->total_pagar,
                    "cliente_id" => $cliente->id,
                    "data_emissao" => date("Y-m-d"),
                    "tipo_movimento" => "-1",
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            // END CONTABILIDADE

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            Alert::warning("Informação", $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        $head = [
            "titulo" => "Movimentos do Stock",
            "descricao" => env("APP_NAME"),
            "loja" => $entidade,
            "factura" => $vendas,
            "items_facturas" => $items,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,
            "motivo" => $motivo,
            "venda_realizado" => $request->venda_realizado,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];
        // Retorna a resposta de sucesso
        return response()->json(["message" => "Pagamento realizado com sucesso!", "data" => $head], 200);
    }

    public function factura_recibo_pos_venda(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $factura = Venda::with(["cliente", "caixa", "user", "pedido.items.produto", "mesa"])->findOrFail($request->factura);

        $items = ItemVenda::with(["produto.motivo"])
            ->where("code", $factura->code)
            ->where("entidade_id", $entidade->empresa->id)
        ->get();

        if ($items) {

            $total_incidencia_ise = 0;
            $total_iva_ise = 0;

            $total_incidencia_nor = 0;
            $total_iva_nor = 0;

            $total_incidencia_out = 0;
            $total_iva_out = 0;

            $total_incidencia_out_5 = 0;
            $total_iva_out_5 = 0;

            $total_incidencia_out_2 = 0;
            $total_iva_out_2 = 0;

            $motivo = "";

            foreach ($items as $item) {
                if ($item->iva_taxa === 14) {
                    $total_incidencia_nor = $total_incidencia_nor + $item->valor_base;
                    $total_iva_nor = $total_iva_nor + $item->valor_iva;
                }
                if ($item->iva_taxa === 0) {
                    $total_incidencia_ise = $total_incidencia_ise + $item->valor_base;
                    $total_iva_ise = $total_iva_ise + $item->valor_iva;
                    $motivo = $item->produto->motivo->descricao;
                }
                if ($item->iva_taxa === 7) {
                    $total_incidencia_out = $total_incidencia_out + $item->valor_base;
                    $total_iva_out = $total_iva_out + $item->valor_iva;
                }
                if ($item->iva_taxa === 2) {
                    $total_incidencia_out_2 = $total_incidencia_out_2 + $item->valor_base;
                    $total_iva_out_2 = $total_iva_out_2 + $item->valor_iva;
                }
                if ($item->iva_taxa === 5) {
                    $total_incidencia_out_5 = $total_incidencia_out_5 + $item->valor_base;
                    $total_iva_out_5 = $total_iva_out_5 + $item->valor_iva;
                }
            }
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "FACTURA RECIBO",
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "descricao" => env("APP_NAME"),
            "loja" => $entidade,
            "factura" => $factura,
            "items_facturas" => $items,

            "total_incidencia_nor" => $total_incidencia_nor,
            "total_iva_nor" => $total_iva_nor,

            "total_incidencia_ise" => $total_incidencia_ise,
            "total_iva_ise" => $total_iva_ise,

            "total_incidencia_out" => $total_incidencia_out,
            "total_iva_out" => $total_iva_out,

            "total_incidencia_out_5" => $total_incidencia_out_5,
            "total_iva_out_5" => $total_iva_out_5,

            "total_incidencia_out_2" => $total_incidencia_out_2,
            "total_iva_out_2" => $total_iva_out_2,

            "motivo" => $motivo,
            "venda_realizado" => $factura->mesa_caixa,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.facturas.documentos.factura-recibo", $head);
    }

    public function cancelar_vendas()
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $movimento = ItemVenda::where([
            ["user_id", Auth::user()->id],
            ["code", NULL],
            ["status", "processo"],
            ["entidade_id", $entidade->empresa->id],
        ])->with("produto")->get();

        if (count($movimento) == 0) {
            Alert::error("Erro", "O correu um erro, não existe nenhum produto selecionado!");
            return redirect()->route("pronto-venda");
        }

        foreach ($movimento as $item) {
            #TODO
            // dd($item);
            $item_venda = ItemVenda::findOrFail($item->id);
            $produto = Produto::with("estoque")->findOrFail($item_venda->produto_id);
            $produto->estoque->stock = $produto->estoque->stock + $item_venda->quantidade;
            $produto->estoque->update();

            $item_venda->delete();
        }

        Alert::success("Sucesso", "Venda interropida com sucesso!");
        return redirect()->route("pronto-venda");
    }


    ############## PROCESSO FACTURA
    public function actualizar_vendas_factura($id)
    {
        $movimento = ItemVenda::with(['produto'])->findOrFail($id);

        $produto = Produto::findOrFail($movimento->produto_id);
        $grupo_precos = ProdutoGrupoPreco::with(['produto'])->where('produto_id', $produto->id)->get();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => env('APP_NAME') . " Pronto Vendas",
            "descricao" => env('APP_NAME'),
            "categorias" => Categoria::with(['produtos.marca', 'produtos.variacao'])
                ->where('entidade_id', $entidade->empresa->id)
                ->get(),
            "dados" => Entidade::findOrFail($entidade->empresa->id),
            "movimento" => $movimento,
            "produto" => $produto,
            "grupo_precos" => $grupo_precos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.vendas.actualizar-venda-factura', $head);
    }

    public function actualizar_vendas_factura_update(Request $request, $id)
    {
        try {
            // Inicia a transação
            DB::beginTransaction();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $movimento = ItemVenda::with(['produto'])->findOrFail($id);

            $produto = Produto::with('estoque')->findOrFail($movimento->produto_id);

            $loja = Loja::where("entidade_id", $entidade->entidade_id)->where("status", "activo")->first();

            if ($produto->tipo == "P") {
                $gestao_quantidade = Estoque::where('loja_id', $loja->id)
                    ->where('produto_id', $produto->id)
                    ->where('stock', '>=', 1)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();
            }


            $IVA_VALOR_VENDA_PRODUTO = $produto->preco_venda * ($produto->taxa / 100);

            $DESCONTO_APLICADO = (float) $request->desconto_aplicado;

            $quantidade_final = $request->input1 * $request->input2 * $request->quantidade;

            $_VALOR_PAGAR = ($request->preco_unitario - $IVA_VALOR_VENDA_PRODUTO) * $quantidade_final;

            $_DESCONTO = $_VALOR_PAGAR * ($DESCONTO_APLICADO / 100);

            $_VALOR_BASE = $_VALOR_PAGAR - $_DESCONTO;

            $_VALOR_IVA = $_VALOR_BASE * ($produto->taxa / 100);

            $_VALOR_RETENCAO = $_VALOR_BASE * ($entidade->empresa->taxa_retencao_fonte / 100);

            $_VALOR_TOTAL = ($_VALOR_BASE + $_VALOR_IVA) -  $_VALOR_RETENCAO;


            $movimento->valor_pagar = $_VALOR_TOTAL;
            $movimento->total = $_VALOR_TOTAL;

            $movimento->custo = $produto->preco_custo * $quantidade_final;
            $movimento->lucro = ((($request->preco_unitario - $IVA_VALOR_VENDA_PRODUTO) - $produto->preco_custo) - $_DESCONTO) * $quantidade_final;
            $movimento->lucro_iva = (($produto->preco_venda_com_iva - $produto->preco_custo) - $_DESCONTO) * $quantidade_final;

            $movimento->desconto_aplicado = $movimento->desconto_aplicado;
            $movimento->desconto_aplicado_valor = $_DESCONTO;

            $movimento->valor_base = $_VALOR_BASE;
            $movimento->valor_iva = $_VALOR_IVA;

            $movimento->preco_unitario = $request->preco_unitario - $_DESCONTO;

            if ($produto->tipo == "S") {
                if ($produto->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
                    $movimento->retencao_fonte = $_VALOR_RETENCAO;
                } else {
                    $movimento->retencao_fonte = 0;
                }
            } else {
                $movimento->retencao_fonte = 0;
            }


            if ($produto->tipo == "P") {
                $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

                if ($update_gestao_quantidade) {
                    $update_gestao_quantidade->stock = $update_gestao_quantidade->stock + $movimento->quantidade;
                    $update_gestao_quantidade->update();
                    $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $quantidade_final;
                    $update_gestao_quantidade->update();
                }
            }

            $movimento->quantidade = $quantidade_final;
            $movimento->update();

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }

        // Retorna a resposta de sucesso
        return response()->json(['message' => 'Pagamento realizado com sucesso!', 'redirect' => route('facturas.create')], 200);

        // return redirect()->route('facturas.create');

    }
}
