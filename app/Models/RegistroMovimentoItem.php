<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class RegistroMovimentoItem extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'registros_movimentos_item';

    protected $fillable = [
        'registro_id',
        'produto_id',
        'quantidade',
        'lote_id',
        'preco_custo',
        'preco_venda',
        'codigo',
        'user_id',
        'entidade_id',
    ];

    public function registro()
    {
        return $this->belongsTo(RegistroMovimento::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }
}
