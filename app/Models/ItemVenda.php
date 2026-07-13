<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class ItemVenda extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'itens_vendas';

    protected $fillable = [
        'produto_id',
        'factura_id',
        'movimento_id',
        'user_id',
        'quantidade',
        'quantidade_devolvida',
        'status',
        'status_uso',
        'caixa_id',
        'mesa_id',
        'quarto_id',
        'valor_iva',
        'valor_base',
        'valor_pagar',
        'retencao_fonte',
        'custo',
        'lucro',
        'lucro_iva',
        'total',
        'preco_unitario',
        'tipo_desconto',
        'desconto_aplicado',
        'desconto_aplicado_valor',
        'iva',
        'iva_taxa',
        'texto_opcional',
        'code',
        'numero_serie',
        'lote_id',
        'entidade_id',
        'user_id',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'factura_id', 'id');
    }

    public function caixa()
    {
        return $this->belongsTo(Caixa::class, 'caixa_id', 'id');
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'id');
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class, 'quarto_id', 'id');
    }

    public function factura()
    {
        return $this->belongsTo(Venda::class, 'factura_id', 'id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // exibir imposto
    public function exibir_imposto_iva($string)
    {
        if ($string == "") {
            return 0;
        } else if ($string == "ISE") {
            return 0;
        } else if ($string == "RED") {
            return 2;
        } else if ($string == "INT") {
            return 5;
        } else if ($string == "OUT") {
            return 7;
        } else if ($string == "NOR") {
            return 14;
        }
    }
}
