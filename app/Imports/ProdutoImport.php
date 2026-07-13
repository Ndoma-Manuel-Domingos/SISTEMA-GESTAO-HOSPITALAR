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
use App\Models\Unidade;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Variacao;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdutoImport implements ToModel, WithHeadingRow
{
    use TraitHelpers;
    
    protected $registro;
    protected $total;
    protected $code;
    protected $loja_id;

    public function __construct($data)
    {
   
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $total_registro = RegistroMovimento::where("entidade_id", $entidade->empresa->id)
            ->where('tipo_documento', "CN")
            ->count() + 1;

        $sigla = "CN" . date('Y') . "/" . $total_registro;

        $this->code = time();
        $this->loja_id = $data['loja_id'];
    
        // Cria o registro principal ao iniciar a importação
        $this->registro = RegistroMovimento::create([
            "operacao" => $data['operacao'],
            "tipo" => $data['tipo_documento'],
            "numero" => $total_registro,
            "codigo" => $this->code,
            "sigla" => $sigla,
            "data_at" => date("Y-m-d"),
            "observacao" => $data['observacao'],
            "loja_id" => $data['loja_id'],
            "cliente_id" => $data['cliente_id'],
            "fornecedor_id" => $data['fornecedor_id'],
            "tipo_documento" => $data['tipo_documento'],
            "user_id" => Auth::user()->id,
            "entidade_id" => $entidade->empresa->id,
        ]);
    }

    public function __destruct()
    {
        // Atualiza a contagem de itens no final
        $this->registro->update(['total' => $this->total]);
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $categoria = Categoria::updateOrCreate([
            'entidade_id' => $entidade->empresa->id,
            'categoria' => $row['categoria'],
        ],
        [
            'categoria' => $row['categoria'],
            "user_id" => Auth::user()->id,
        ]);
        
        $marca = Marca::updateOrCreate([
            'entidade_id' => $entidade->empresa->id,
            'nome' => '-- Sem Marca --',
        ],
        [
            'nome' => '-- Sem Marca --',
            "user_id" => Auth::user()->id,
        ]);
        
        $variacao = Variacao::updateOrCreate([
            'entidade_id' => $entidade->empresa->id,
            'nome' => '-- Sem Variação --',
        ],
        [
            'nome' => '-- Sem Variação --',
            "user_id" => Auth::user()->id,
        ]);

        $unidade = Unidade::where('sigla', $row["unidade"])->first();

        $produto_ = Produto::where("entidade_id", $entidade->empresa->id)->where("codigo_barra", $row["codigo_barra"])->first();
        
        if (!$produto_) {

            $code = uniqid(time());
            $nova_conta = "";

            if ($row["tipo"] == "S" || $row["tipo"] == "Serviço" || $row["tipo"] == "Servico" || $row["tipo"] == "servico" || $row["tipo"] == "serviço") {
                // 26.1
                $conta = Conta::where("conta", "62")->where("entidade_id", $entidade->empresa->id)->first();
                $serie = "62.1.1";

                $qtds = 0;
                $observacao = "Registro de serviço";
            } else {
                // 26.1 - MERCADORIAS
                $conta = Conta::where("conta", "26")->where("entidade_id", $entidade->empresa->id)->first();
                $serie = "26.1";

                $qtds = $row["quantidade"];
                $observacao = "Entrada de Existência";
            }

            $subc_ = Subconta::where("numero", "like", $serie . "%")->where("entidade_id", $entidade->empresa->id)->count();
            $numero =  $subc_ + 1;

            $nova_conta = $serie . "." . $numero;

            $subconta = Subconta::create([
                "entidade_id" => $entidade->empresa->id,
                "numero" => $nova_conta,
                "nome" => $row["nome"] ?? NULL,
                "tipo_conta" => "M",
                "code" => $code,
                "status" => $conta->status,
                "conta_id" => $conta->id,
                "user_id" => Auth::user()->id,
            ]);

            $preco_custo = (float) $this->normalizarNumero($row['preco_custo'] ?? 0);
            $preco_venda = (float) $this->normalizarNumero($row['preco_venda'] ?? 0);
            $preco_custo_media = (float) isset($row["preco_custo_media"]) ? $this->normalizarNumero($row['preco_custo_media'] ?? 0) : $preco_custo;

            $movimeto = Movimento::create([
                "user_id" => Auth::user()->id,
                "subconta_id" => $subconta->id,
                "status" => true,
                "movimento" => "E",
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                "credito" => 0,
                "debito" => $preco_custo * $qtds,
                "observacao" => $observacao,
                "code" => $code,
                "data_at" => date("Y-m-d"),
                "entidade_id" => $entidade->empresa->id,
                "exercicio_id" => $this->exercicio(),
                "periodo_id" => $this->periodo(),
            ]);

            $motivo = Motivo::find($entidade->empresa->motivo_id);

            if (isset($row["Iva"])) {
                $imposto = Imposto::where('valor', $row["Iva"])->where('entidade_id', $entidade->empresa->id)->first();
            } else {
                $imposto = Imposto::find($entidade->empresa->imposto_id);
            }
            

            $codig = $row["codigo_barra"] ?? time();

            $produto = Produto::create([
                "nome" => $row["nome"] ?? NULL,
                "status" => "activo",
                "conta" => $nova_conta,
                "code" => $code,
                "referencia" => $codig,
                "codigo_barra" => $codig,
                "descricao" => $row["nome"] ?? NULL,
                "incluir_factura" => "Não",
                "imagem" => NULL,
                "variacao_id" => $variacao->id ?? NULL,
                "categoria_id" => $categoria->id ?? NULL,
                "marca_id" => $marca->id ?? NULL,
                "motivo_id" => $motivo ? $motivo->id : NULL,
                "imposto_id" => $imposto ? $imposto->id : NULL,
                "tipo" => $row["tipo"] ?? "P",
                "unidade_id" => $unidade ? $unidade->id : 6,
                "imposto" => $imposto ? $imposto->codigo : NULL,
                "taxa" => $imposto ? $imposto->valor : NULL,
                "motivo_isencao" => $motivo ? $motivo->codigo : NULL,
                "preco_custo" => (float) $preco_custo,
                "margem" => 0,
                "preco_venda" => (float) $preco_venda,
                "preco_venda_com_iva" => (float) $preco_venda,
                "preco" => (float) $preco_custo_media,
                "controlo_stock" =>  $row["tipo"] == "P" ? "Sim" : "Não",
                "tipo_stock" => "M",
                "disponibilidade" => 1,
                "subconta_id" => $subconta->id ?? 1,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            $lote = null;

            if ($row["tipo"] == "P") {
                $lote = Lote::create([
                    "produto_id" => $produto->id,
                    "lote" => $this->gerarLetrasAleatorias() . "-" . $this->gerarNumeroAleatorio(),
                    "status" => "activo",
                    "codigo_barra" => $produto->codigo_barra,
                    "data_validade" => NULL,
                    "data_validade_vitalicio" => 1,
                    "stock_total" => 0,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            // $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

            // // $verifica se tem uma loja activa onde esta sendo retidados os produtos
            // $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            //     ->whereIn("id", $minhas_lojas)
            //     ->get();

            
            LojaProduto::create([
                "produto_id" => $produto->id,
                "loja_id" => $this->loja_id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            $update_estoque = Estoque::where("loja_id", $this->loja_id)
                ->where("produto_id", $produto->id)
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if ($update_estoque) {
                $update_estoque_up = Estoque::findOrFail($update_estoque->id);
                $update_estoque_up->lote_id = $lote ? $lote->id : NULL;

                $update_estoque_up->stock = $update_estoque_up->stock + $qtds;
                $update_estoque_up->update();
            }

            $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                ->where("produto_id", $produto->id)
                ->where("loja_id", $this->loja_id)
            ->first();

            if ($verificarEstoque_) {
                $update = Estoque::findOrFail($verificarEstoque_->id);
                $update->stock = $update->stock + $qtds;
                $update->update();
            } else {
                $estoque = Estoque::create([
                    "loja_id" => $this->loja_id,
                    "lote_id" => $lote ? $lote->id : NULL,
                    "produto_id" => $produto->id,
                    "user_id" => Auth::user()->id,
                    "data_operacao" => date("Y-m-d"),
                    "stock" => $qtds,
                    "observacao" => "Entrada inicial de produtos de Stock",
                    "stock_minimo" => 5, // quantidade minima
                    "operacao" => "Actualizar de Stock",
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }

            if ($row["tipo"] == "P") {
                Registro::create([
                    "registro" => "Entrada de Stock",
                    "data_registro" => date("Y-m-d"),
                    "tipo" => "E",
                    'status' => 'A',
                    "quantidade" => $qtds,
                    "produto_id" => $produto->id,
                    "observacao" => "Entrada inicial de produtos de Stock",
                    "loja_id" => $this->loja_id,
                    "lote_id" => $lote ? $lote->id : NULL,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }
           

            RegistroMovimentoItem::create([
                'registro_id' => $this->registro->id,
                'codigo' => $this->code,
                'produto_id' => $produto->id,
                'quantidade' => $qtds,
                'preco_custo' => $produto->preco_custo,
                'preco_venda' => $produto->preco_venda,
                'lote_id' => $lote ? $lote->id : NULL,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);
            
            $this->total += $produto->preco_custo * $qtds;

            return $produto;
        }else {
                
            if ($row["tipo"] == "S" || $row["tipo"] == "Serviço" || $row["tipo"] == "Servico" || $row["tipo"] == "servico" || $row["tipo"] == "serviço") {
                // 26.1
                $conta = Conta::where("conta", "62")->where("entidade_id", $entidade->empresa->id)->first();
                $serie = "62.1.1";

                $qtds = 0;
                $observacao = "Registro de serviço";
            } else {
                // 26.1 - MERCADORIAS
                $conta = Conta::where("conta", "26")->where("entidade_id", $entidade->empresa->id)->first();
                $serie = "26.1";

                $qtds = $row["quantidade"];
                $observacao = "Entrada de Existência";
            }
            
            $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                ->where("produto_id", $produto_->id)
                ->where("loja_id", $this->loja_id)
            ->first();
            
            if ($verificarEstoque_) {
                $update = Estoque::findOrFail($verificarEstoque_->id);
                $update->stock += $qtds;
                $update->update();
            }
            
            $lote = Lote::where("status", "activo")
                ->where("entidade_id", $entidade->empresa->id)
                ->where("produto_id", $produto_->id)
            ->first();
            
            RegistroMovimentoItem::create([
                'registro_id' => $this->registro->id,
                'codigo' => $this->code,
                'produto_id' => $produto_->id,
                'quantidade' => $qtds,
                'preco_custo' => $produto_->preco_custo,
                'preco_venda' => $produto_->preco_venda,
                'lote_id' => $lote ? $lote->id : NULL,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);
            
            $this->total += $produto_->preco_custo * $qtds;
            
            return $produto_;
        }
        
        return null;
    }

    function normalizarNumero($valor)
    {
        return $valor;
    }
}
