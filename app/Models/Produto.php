<?php

namespace App\Models;

use App\Support\UnitConverter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class Produto extends Model
{

    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'referencia',
        'codigo_barra',
        'peso',
        'conta',
        'code',
        'descricao',
        'incluir_factura',
        'imagem',
        'variacao_id',
        'categoria_id',
        'subconta_id',
        'marca_id',
        'type_model_id',
        'motivo_id',
        'imposto_id',
        'tipo',
        'unidade_id',
        'imposto',
        'taxa',
        'motivo_isencao',
        'preco_custo',
        'margem',
        'preco_venda',
        'preco_venda_com_iva',
        'preco',
        'modo_tarefario',
        'tipo_cobranca',
        'aplicado',
        'controlo_stock',
        'tipo_stock',
        'disponibilidade',
        'user_id',
        'entidade_id',
    ];

    protected $appends = ['total_produto_loja_activa'];

    public function converterParaBase(float $quantidade, Unidade $unidade)
    {
        return UnitConverter::converterParaBase($quantidade, $unidade);
    }

    public function converterDaBase(float $quantidade, Unidade $unidade)
    {
        return UnitConverter::converterDaBase($quantidade, $unidade);
    }

    public function receitas()
    {
        return $this->hasMany(ProdutoReceita::class, 'produto_id', 'id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id', 'id');
    }

    public function paramentros()
    {
        return $this->hasMany(ParamentroExame::class, 'exame_id', 'id');
    }

    public function paramentros_consulta()
    {
        return $this->hasMany(ParamentroConsulta::class, 'consulta_id', 'id');
    }

    public function tarefarios()
    {
        return $this->hasMany(QuartoTarefario::class, 'tarefario_id', 'id');
    }

    public function lote()
    {
        return $this->hasOne(Lote::class, 'lote_id', 'id');
    }

    public function verificar_lote(string $id_produto, string $empresa_id)
    {
        $produto = Produto::findOrFail($id_produto);

        $lote = Lote::where("entidade_id", $empresa_id)
            ->where("status", "expirado")
            // ->where("codigo_barra", $produto->codigo_barra)
            ->where("produto_id", $produto->id)
            ->first();

        return $lote;
    }

    // verificar se este produto tem quantidade para ser vendidas no lote não expirado, isto por que  não podemos permitir o sistema vender quantidades que não existem
    public function verificar_lote_produto(string $id_produto, $lote_id, string $empresa_id)
    {
        try {
            DB::beginTransaction();
            //

            $registros = Registro::where('entidade_id', $empresa_id)->where("produto_id", $id_produto)
                ->selectRaw('SUM(CASE WHEN tipo = "E" THEN quantidade ELSE 0 END) - SUM(CASE WHEN tipo = "S" THEN quantidade ELSE 0 END) as total_estoque')
                ->first();

            //$total_estoque_activo = Lote::join("registros", "lotes_validade_produtos.id", "=", "registros.lote_id")
            // ->where("registros.entidade_id", $empresa_id)
            // ->where("lotes_validade_produtos.id", $lote_id)
            // ->where("lotes_validade_produtos.produto_id", $id_produto)
            // ->where("lotes_validade_produtos.status", "activo")
            // ->selectRaw('SUM(CASE WHEN registros.tipo = "E" THEN registros.quantidade ELSE 0 END) - SUM(CASE WHEN registros.tipo = "S" THEN registros.quantidade ELSE 0 END) as total_estoque')
            //  ->get();
            $result = 0;

            if ($registros) {
                $result = (float) $registros->total_estoque;
            } else {
                $result = 0;
            }

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return $result;
    }

    public function taxa_imposto()
    {
        return $this->belongsTo(Imposto::class, 'imposto_id', 'id');
    }

    public function motivo()
    {
        return $this->belongsTo(Motivo::class, 'motivo_id', 'id');
    }

    public function quantidade($loja_id = "")
    {
        $user = auth()->user();

        return $this->belongsTo(Registro::class, 'id', 'produto_id')
            ->where('loja_id', $loja_id)
            ->where('entidade_id', $user->entidade_id);
    }

    public function quantidade_entrada($loja_id = "")
    {
        $user = auth()->user();

        return Registro::where('loja_id', $loja_id)
            ->where('produto_id', $this->id)
            ->where('tipo', 'E')
            ->where('entidade_id', $user->entidade_id)
            ->sum('quantidade');
    }

    public function quantidade_saida($loja_id = "", $user_id = "")
    {
        $user = auth()->user();

        return Registro::where('loja_id', $loja_id)
            ->where('produto_id', $this->id)
            ->whereDate('user_id', $user_id)
            ->where('tipo', 'S')
            ->where('entidade_id', $user->entidade_id)
            ->sum('quantidade');
    }

    public function valor_retencao_total()
    {
        $user = auth()->user();

        return $this->belongsTo(ItemVenda::class, 'id', 'produto_id')
            ->where('produto_id', $this->id)
            ->where('entidade_id', $user->entidade_id)
            ->sum('retencao_fonte');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function variacao()
    {
        return $this->belongsTo(Variacao::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function type_model()
    {
        return $this->hasOne(Turma::class, 'type_model_id', 'id');
    }

    public function estoque()
    {
        $result = $this->hasOne(Estoque::class, 'produto_id', 'id')->with(['loja' => function ($query) {
            $query->where('status', 'activo');
        }]);

        return $result;
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }

    public function item()
    {
        return $this->hasOne(ItemVenda::class);
    }

    public function vendas()
    {
        return $this->hasMany(ItemVenda::class);
    }

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    public function stocks()
    {
        return $this->hasMany(Estoque::class);
    }

    public function lojas()
    {
        return $this->hasMany(LojaProduto::class);
    }

    // exibir imposto
    public function exibir_imposto(string $string)
    {
        if ($string == "") {
            return "Auto";
        } else if ($string == "ISE") {
            return "0%";
        } else if ($string == "RED") {
            return "2%";
        } else if ($string == "INT") {
            return "5%";
        } else if ($string == "OUT") {
            return "7%";
        } else if ($string == "NOR") {
            return "14%";
        }
    }

    public function alert(string $item)
    {
        if ($item > 50) {
            return "<td class='text-danger'>Alerta</td>Excesso</td>";
        }
        if ($item <= 10) {
            return "<td class='text-warning'>Alerta</td>";
        }
        if ($item > 10 and $item <= 50) {
            return "<td class='text-success'>Normal</td>";
        }
    }

    public function getTotalProdutoLojaActivaAttribute()
    {
        return $this->total_produto_loja_activa();
    }


    public function total_produto_loja_activa()
    {
        return Estoque::where('produto_id', $this->id)
            ->where('entidade_id', auth()->user()->entidade_id)
            ->sum('stock');
    }

    public function total_produto_minimo_loja_activa()
    {
        return Estoque::where('produto_id', $this->id)
            ->where('entidade_id', auth()->user()->entidade_id)
            ->sum('stock_minimo');
    }


    public function total_produto($loja_id = "")
    {
        $user = auth()->user();

        $totalStock = Estoque::where('entidade_id', $user->entidade_id)
            ->where('produto_id', $this->id)
            ->where('loja_id', $loja_id)
            ->sum('stock');

        return $totalStock;
    }

    public function total_produto_por_loja(string $id, string $loja_id)
    {
        $totalStock = Estoque::where('produto_id', $id)->where('loja_id', $loja_id)->sum('stock');

        return $totalStock;
    }

    public function codigo_barra_produto(string $id)
    {
        $totalStock = Estoque::where('codigo_barra', $id)
            ->id;

        return $totalStock;
    }
}
