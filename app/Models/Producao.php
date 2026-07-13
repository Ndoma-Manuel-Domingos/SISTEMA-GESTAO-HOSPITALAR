<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producao extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'producao';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'produto_id',
        'receita_id',
        'quantidade_perdida',
        'quantidade_produzida',
        'quantidade_desejada',
        'quantidade_estimada',
        'quantidade_diferenca',
        'fator_escala',
        'perda_gramas',
        'massa_total_gramas',
        'status',
        'loja_id',
        'user_id',
        'entidade_id',
    ];

    public function receita()
    {
        return $this->belongsTo(ProdutoReceita::class, 'receita_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
