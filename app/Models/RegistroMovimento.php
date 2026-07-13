<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class RegistroMovimento extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'registros_movimentos';

    protected $fillable = [
        'loja_id',
        'operacao',
        'observacao',
        'tipo',
        'numero',
        'codigo',
        'data_at',
        'sigla',
        'total',
        'fornecedor_id',
        'cliente_id',
        'tipo_documento',
        'user_id',
        'entidade_id',
    ];

    public function descricao_tipo($string)
    {
        if ($string == "E") {
            return "Entrada";
        }
        if ($string == "S") {
            return "Saida de Produto";
        }
        if ($string == "CF") {
            return "Comprar ao Forncedor";
        }
        if ($string == "SP") {
            return "Saida de Produto";
        }
        if ($string == "DM") {
            return "Devolução de Mercadorias";
        }

        return $this->hasMany(RegistroMovimentoItem::class, 'registro_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(RegistroMovimentoItem::class, 'registro_id', 'id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedore::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
