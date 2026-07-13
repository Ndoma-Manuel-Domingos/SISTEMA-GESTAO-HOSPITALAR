<?php

namespace App\Services;

use App\Http\Controllers\TraitHelpers;
use App\Models\Estoque;
use App\Models\Ingrediente;
use App\Models\IngredienteMovimento;
use App\Models\Loja;
use App\Models\Lote;
use App\Models\Producao;
use App\Models\ProducaoItem;
use App\Models\Produto;
use App\Models\ProdutoReceita;
use App\Models\Registro;
use App\Models\UserLoja;
use Illuminate\Support\Facades\DB;


use Exception;
use Illuminate\Support\Facades\Auth;

class ProducaoService
{
    
    use TraitHelpers;

    public function create($data)
    {
        DB::beginTransaction();

        try {

            $receita = ProdutoReceita::with(['items.ingrediente', 'produto'])->findOrFail($data['receita_id']);

            // =========================
            // FATOR ESCALA
            // =========================

            $factor = $data['quantidade_desejada'] / $receita->rendimento_base;

            // =========================
            // MASSA TOTAL
            // =========================

            $massaTotal = 0;

            foreach ($receita->items as $item) {
                $massaTotal += $item->quantidade_gramas * $factor;
            }

            // =========================
            // PERDA
            // =========================

            $lossPercent = $receita->porcentagem_perda;

            $lossGrams =  $massaTotal * ($lossPercent / 100);

            // =========================
            // MASSA LÍQUIDA
            // =========================

            $massaLiquida = $massaTotal - $lossGrams;

            // =========================
            // QUANTIDADE ESTIMADA
            // =========================

            $estimatedQuantity = floor($massaLiquida / $receita->peso);

            // =========================
            // DIFERENÇA
            // =========================

            $difference = $data['quantidade_desejada'] - $estimatedQuantity;

            // =========================
            // PRODUÇÃO
            // =========================
            $producao = Producao::create([
                'code' => 'PROD-' . time(),
                'produto_id' => $receita->produto_id,
                'receita_id' => $receita->id,
                'quantidade_desejada' => $data['quantidade_desejada'],
                'quantidade_estimada' => $estimatedQuantity,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'quantidade_diferenca' => $difference,
                'fator_escala' => $factor,
                'perda_gramas' => $lossGrams,
                'massa_total_gramas' => $massaTotal,
                'status' => 'PENDENTE',
                'user_id' => Auth::user()->id,
                'entidade_id' => Auth::user()->entidade_id,
            ]);

            // =========================
            // ITEMS PRODUÇÃO
            // =========================
         
            foreach ($receita->items as $item) {
                // $required = $item->quantidade * $factor;
                
                $quantidade_gramas = $item->quantidade_gramas;
                
                // validar stock
                if ($item->ingrediente->total_produto_loja_activa < $quantidade_gramas) {
                    throw new Exception('Stock insuficiente de ' . $item->ingrediente->nome);
                }
                // salvar item produção
                ProducaoItem::create([
                    'producao_id' => $producao->id,
                    'ingrediente_id' => $item->ingrediente_id,
                    'quantidade_planejada_gramas' => $quantidade_gramas,
                    'quantidade_usada_gramas' => $quantidade_gramas,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => Auth::user()->entidade_id,
                ]);
                
                // reduzir stock
                $this->removeStock($item->ingrediente_id, $quantidade_gramas, $producao);
            }
            
            DB::commit();
            return $producao;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // =========================
    // REDUZIR STOCK
    // =========================

    private function removeStock(int $produtoId, float $grams, Producao $producao)
    {
        $produto = Produto::findOrFail($produtoId);
        
        // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $loja = Loja::where("entidade_id", $produto->entidade_id)
        //     ->where("status", "activo")
        //     ->whereIn("id", $minhas_lojas)
        //     ->first();
        
        $loja = $this->LOJA_ACTIVA_USER();
        
        // SElecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
        $lote = Lote::where("produto_id", $produto->id)
            ->where("codigo_barra", $produto->codigo_barra)
            ->where("entidade_id", $produto->entidade_id)
        ->first();

        if ($lote && $lote->status == "expirado" && $lote->data_validade <= date("Y-m-d")) {
            return response()->json(['message' => "O produto: { $produto->nome } parece estar expirado, por isso não é possível finalizar a venda, visando a segurança da população."], 404);
        }
        if (!$loja) {
            return response()->json(["message" => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"], 400);
        }
    
        // verificar quantidade de produto no estoque da loja
        $verificar_quantidade = Estoque::where('loja_id', $loja->id)
            ->where('produto_id', $produto->id)
            ->where('stock', '>=', 0)
            ->where('entidade_id', $produto->entidade_id)
        ->sum('stock');

        $gestao_quantidade = Estoque::where('loja_id', $loja->id)
            ->where('produto_id', $produto->id)
            ->where('stock', '>=', 0)
            ->where('entidade_id', $produto->entidade_id)
        ->first();

        $verificar_quantidade = (float) $verificar_quantidade;

        if ($grams > $verificar_quantidade) {
            return response()->json([
                'message' => "Stock insuficiente para produção. Disponível: {$verificar_quantidade}. Solicitado: {$grams}."
            ], 400);
        }
        
        if ($grams <= 0) {
            return response()->json([
                'message' => 'Quantidade inválida. Informe um valor maior que zero para continuar.'
            ], 400);
        }
    
        Registro::create([
            "registro" => "Saída de Stock",
            "documento" =>  $producao->code,
            "documento_id" => $producao->id,
            "data_registro" => date('Y-m-d'),
            "preco_unitario" => $produto->preco_custo,
            "quantidade" => $grams,
            "produto_id" => $produto->id,
            "observacao" => "Saída do produto {$produto->nome} para venda",
            "loja_id" => $loja->id,
            "tipo" => "S",
            "status" => "V",
            "lote_id" => $lote ? $lote->id : NULL,
            "user_id" => Auth::user()->id,
            "entidade_id" => $produto->entidade_id,
        ]);

        $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

        if ($update_gestao_quantidade) {
            $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $grams;
            $update_gestao_quantidade->update();
        }
    }
}
