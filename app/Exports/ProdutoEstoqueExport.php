<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Entidade;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ProdutoEstoqueExport implements FromView, WithTitle, WithCustomStartCell
{
    
    use TraitHelpers;

    private $data_inicio; 
    private $data_final; 
    private $loja_id; 
    private $apenas_com_quantidade; 
    private $tipo_preco; 
    private $categoria_id;
    private $isMateriaPrima;

    public function __construct(Request $request, bool $isMateriaPrima = false)
    {
        $this->data_inicio = $request->data_inicio;
        $this->data_final = $request->data_final;
        $this->loja_id = $request->loja_id;
        $this->tipo_preco = $request->tipo_preco;
        $this->apenas_com_quantidade = $request->apenas_com_quantidade;
        $this->categoria_id = $request->categoria_id;
        $this->isMateriaPrima = $isMateriaPrima;
    }


    public function title(): string
    {
        return $this->isMateriaPrima ? "Matérias-Primas no Stock" : "Produtos no Stock";
    }


    public function startCell(): string
    {
        return 'A11';
    }

    public function view(): View
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);

        $data_inicio = $this->data_inicio;
        $data_final = $this->data_final;
        $loja_id = $this->loja_id;
        $tipo_preco = $this->tipo_preco;
        $apenas_com_quantidade = $this->apenas_com_quantidade;
        $categoria_id = $this->categoria_id;

        $produtos = Produto::with(["vendas" => function ($query) use ($data_inicio, $data_final, $loja_id) {
            // Filtrar as vendas com base nas datas fornecidas pelo usuário
            $query->when($data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::parse($value));
            })
                ->when($data_final, function ($query, $value) {
                    $query->whereDate("created_at", "<=", Carbon::parse($value));
                });
            $query->where("status", "!=", "anulada");
        }, "stocks" => function ($query) use ($loja_id, $data_final) {
            // Filtrar os estoques com base na data final fornecida pelo usuário
            $query->when($data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::parse($value));
            });
            $query->when($loja_id, function ($query, $value) {
                $query->where("loja_id", $value);
            });
        }])
         ->when($this->isMateriaPrima,
            fn($q) => $q->where('tipo_stock', 'P'),
            fn($q) => $q->where('tipo_stock', '!=', 'P')
        )
        ->when($categoria_id, function ($query, $value) {
            $query->where("categoria_id", $value);
        })
        ->where("entidade_id", $entidade->empresa->id)
        ->orderBy("nome")
        ->get();

        // Preparar os dados para a resposta
        $dados = $produtos->map(function ($produto) use ($data_inicio, $data_final) {
            $dataInicio = $data_inicio ? Carbon::parse($data_inicio) : Carbon::now()->startOfDay();
            $dataFinal = $data_final ? Carbon::parse($data_final) : Carbon::now()->endOfDay();

            // totdal vendido
            $quantidadeVendida = $produto->vendas->whereBetween("created_at", [$dataInicio, $dataFinal])->sum("quantidade");

            $totalVendida = $produto->vendas->whereBetween("created_at", [$dataInicio, $dataFinal])->sum("valor_pagar");
            $totalCusto = $produto->vendas->whereBetween("created_at", [$dataInicio, $dataFinal])->sum("custo");
            $totalLucro = $produto->vendas->whereBetween("created_at", [$dataInicio, $dataFinal])->sum("lucro");

            // Calcular a quantidade em estoque até a data final especificada
            // $quantidadeEmEstoque = $produto->stocks->where("created_at", "<=", $dataFinal)->sum("stock");
            $quantidadeEmEstoque = $produto->converterDaBase($produto->stocks->sum("stock"), $produto->unidade);

            // Calcular a quantidade restante
            $quantidadeRestante = $quantidadeEmEstoque - $quantidadeVendida;

            // Calcular a quantidade inicial
            $quantidadeInicial = $quantidadeEmEstoque + $quantidadeVendida;

            return (object) [
                "id" => $produto->id,
                "codigo_barra" => $produto->codigo_barra,
                "produto" => $produto->nome,
                "preco" => $produto->preco_venda,
                "preco_custo" => $produto->preco_custo,
                "imposto" => $produto->taxa,
                "desconto" => 0,
                "total_liquido_vendido" => $totalVendida,
                "total_liquido_custo" => $totalCusto,
                "total_liquido_lucro" => $totalLucro,
                "total_liquido_restante" => $produto->preco_venda * $quantidadeInicial,
                "total_liquido_geral" => $produto->preco_custo * $quantidadeEmEstoque,
                "quantidade_inicial" => $quantidadeInicial,
                "quantidade_vendida" => $quantidadeVendida,
                "quantidade_estoque" => $quantidadeEmEstoque,
                "quantidade_restante" => $quantidadeRestante,
            ];
        })
            ->when($apenas_com_quantidade == true, function ($collection) use ($apenas_com_quantidade) {
                if ($apenas_com_quantidade == "true") {
                    return $collection->filter(fn($item) => $item->quantidade_inicial > 0);
                } else if ($apenas_com_quantidade == "false") {
                    return $collection->filter(fn($item) => $item->quantidade_inicial <= 0);
                } else {
                    return $collection->filter(fn($item) => $item->quantidade_inicial > 10);
                }
            })
            ->values();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $loja = Loja::find($loja_id);

        return view('exports.produtos-stock', [
            "titulo" => $this->isMateriaPrima ? "Matérias-Primas no Stock" : "Produtos no Stock",
            "descricao" => env('APP_NAME'),
            'dados' => $dados,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "loja" => $loja,
            "tipo_preco" => $tipo_preco,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ]);
    }
}
