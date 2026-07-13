<?php

namespace App\Imports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Estoque;
use App\Models\Imposto;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Marca;
use App\Models\Motivo;
use App\Models\Movimento;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\RegistroMovimentoItem;
use App\Models\Subconta;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Variacao;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EstoqueImport implements ToModel, WithHeadingRow
{
    use TraitHelpers;

    protected $formData;

    public function __construct($formData)
    {
        $this->formData = $formData;
    }


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($this->formData->operacao == "Saída de Stock") {
            $tipo = "S";
        } else {
            $tipo = "E";
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produto = Produto::where("entidade_id", $entidade->empresa->id)
            ->where(function ($query) use ($row) {
                $query->where("codigo_barra", $row["codigo_barra"])
                    ->orWhere("nome", $row["nome"]);
            })
            ->first();


        $total_registro = RegistroMovimento::where("entidade_id", $entidade->empresa->id)
            ->where('tipo_documento', $this->formData->tipo_documento)
            ->count() + 1;

        $sigla = $this->formData->tipo_documento . "" . date('Y') . "/" . $total_registro;


        if ($produto) {

            $lote = Lote::where("produto_id", $produto->id)->where("entidade_id", $entidade->empresa->id)->first();

            if ($lote) {

                if ($this->formData->operacao == "Saída de Stock") {
                    Registro::create([
                        "documento" => $sigla,
                        "registro" => $this->formData->operacao,
                        "data_registro" => date("Y-m-d"),
                        "tipo" => $tipo,
                        'status' => 'E',
                        "quantidade" => $row["quantidade"],
                        "preco_unitario" => $row["preco_custo"],
                        "produto_id" => $produto->id,
                        "observacao" => $this->formData->observacao,
                        "loja_id" => $this->formData->loja_id,
                        "lote_id" => $lote->id,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    $verificarEstoque = Estoque::where("lote_id", $lote->id)
                        ->where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $produto->id)
                        ->where("loja_id", $this->formData->loja_id)
                        ->first();

                    if ($verificarEstoque) {
                        // Nao informou o lote
                        if (!$lote->id == NULL) {
                            $produtos_lotes = Lote::findOrFail($lote->id);
                            if ($row["quantidade"] > $produtos_lotes->stock_total) {
                                $produtos_lotes->stock_total = $produtos_lotes->stock_total - $row["quantidade"];
                                $produtos_lotes->saida = $produtos_lotes->saida - $row["quantidade"];
                                $produtos_lotes->update();
                            }
                        }
                        $saida =  Estoque::findOrFail($verificarEstoque->id);
                        if ($saida->stock > $row["quantidade"]) {
                            $saida->stock = $saida->stock - $row["quantidade"];
                            $saida->update();
                        } else {
                            return response()->json(['message' => "Não pode retiriar mais do que a quantidade existente!"], 404);
                        }
                    }
                }

                if ($this->formData->operacao == "Entrada de Stock") {

                    ## DEFINIR PRECO CUSTO EM MEDIA

                    ## ANTIGO
                    $TOTAL_CUSTO_ANTIGO = $produto->preco_custo * $produto->total_produto_loja_activa;
                    $TOTAL_CUSTO_NOVO = $row["preco_custo"] * $row["quantidade"];

                    $TOTAL_CUSTO = $TOTAL_CUSTO_ANTIGO + $TOTAL_CUSTO_NOVO;

                    $TOTAL_QUANTIDADE_FINAL = $produto->total_produto_loja_activa + $row["quantidade"];

                    $CUSTO_MEDICO = $TOTAL_CUSTO / $TOTAL_QUANTIDADE_FINAL;

                    $produto->disponibilidade = $produto->preco_custo;
                    $produto->preco = $CUSTO_MEDICO;
                    $produto->preco_custo = $row["preco_custo"];
                    $produto->preco_venda = $row["preco_venda"];
                    $produto->update();

                    Registro::create([
                        "documento" => $sigla,
                        "registro" => $this->formData->operacao,
                        "data_registro" => date("Y-m-d"),
                        "tipo" => $tipo,
                        "status" => "A",
                        "quantidade" => $row["quantidade"],
                        "produto_id" => $produto->id,
                        "observacao" => $this->formData->observacao,
                        "loja_id" => $this->formData->loja_id,
                        "lote_id" => $lote->id,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    if (!$lote->id == NULL) {
                        $produtos_lotes = Lote::findOrFail($lote->id);
                        $produtos_lotes->stock_total = $produtos_lotes->stock_total + $row["quantidade"];
                        $produtos_lotes->entrada = $produtos_lotes->entrada + $row["quantidade"];
                        $produtos_lotes->update();
                    }

                    $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $produto->id)
                        ->where("loja_id", $this->formData->loja_id)
                        ->first();

                    if ($verificarEstoque_) {
                        $update = Estoque::findOrFail($verificarEstoque_->id);
                        $update->stock = $update->stock + $row["quantidade"];
                        $update->update();
                    } else {
                        Estoque::create([
                            "loja_id" => $this->formData->loja_id,
                            "lote_id" => $lote->id,
                            "produto_id" => $produto->id,
                            "user_id" => Auth::user()->id,
                            "data_operacao" => date("Y-m-d"),
                            "stock" => $row["quantidade"],
                            "operacao" => $this->formData->operacao,
                            "observacao" => $this->formData->observacao,
                            "entidade_id" => $entidade->empresa->id,
                        ]);
                    }
                }

                if ($this->formData->operacao == "Actualizar de Stock") {
                    Registro::create([
                        "registro" => $this->formData->operacao,
                        "documento" => $sigla,
                        "data_registro" => date('Y-m-d'),
                        "tipo" => $tipo,
                        'status' => 'A',
                        "quantidade" => $row["quantidade"],
                        "produto_id" => $produto->id,
                        "observacao" => $this->formData->observacao,
                        "loja_id" => $this->formData->loja_id,
                        "lote_id" => $lote->id,
                        "user_id" => Auth::user()->id,
                        "entidade_id" => $entidade->empresa->id,
                    ]);

                    $verificarEstoque = Estoque::where("lote_id", $lote->id)
                        ->where("entidade_id", $entidade->empresa->id)
                        ->where("produto_id", $produto->id)
                        ->where("loja_id", $this->formData->loja_id)
                        ->first();

                    // Nao informou o lote
                    if (!$lote->id == NULL) {
                        $produtos_lotes = Lote::findOrFail($lote->id);
                        $produtos_lotes->stock_total = $row["quantidade"];
                        $produtos_lotes->entrada = $row["quantidade"] - $produtos_lotes->stock;
                        $produtos_lotes->update();
                    }
                    if ($verificarEstoque) {
                        $saida = Estoque::findOrFail($verificarEstoque->id);
                        $saida->stock = $row["quantidade"] - $saida->stock;
                        $saida->update();
                    }
                }
            }

            $code = time();

            $registro = RegistroMovimento::create([
                "operacao" => $this->formData->operacao,
                "tipo" => $this->formData->tipo_documento,
                "numero" => $total_registro,
                "codigo" => $code,
                "sigla" => $sigla,
                "data_at" => date("Y-m-d"),
                "observacao" => $this->formData->observacao,
                "loja_id" => $this->formData->loja_id,
                "cliente_id" => $this->formData->cliente_id ?? NULL,
                "fornecedor_id" => $this->formData->fornecedor_id ?? NULL,
                "tipo_documento" => $this->formData->tipo_documento,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            $total = 0;
            if ($lote) {
                RegistroMovimentoItem::create([
                    'registro_id' => $registro->id,
                    'codigo' => $code,
                    'produto_id' => $produto->id,
                    'quantidade' => $row["quantidade"],
                    'preco_custo' => $row['preco_custo'],
                    'preco_venda' => $row['preco_venda'],
                    'lote_id' => $lote->id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                $total += $row['preco_custo'] * $row['quantidade'];
            }

            $registro->total = $total;
            $registro->update();

            return $produto;
        }

        return null;
    }

    function normalizarNumero($valor)
    {
        return $valor;
    }
}
