<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoReceita extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'produtos_receitas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produto_id',
        'nome',
        'rendimento_base',
        'peso',
        'porcentagem_perda',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function items()
    {
        return $this->hasMany(ProdutoReceitaItem::class, 'receita_id', 'id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
