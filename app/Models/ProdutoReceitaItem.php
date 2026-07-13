<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoReceitaItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'produtos_receitas_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'receita_id',
        'ingrediente_id',
        'quantidade',
        'quantidade_gramas',
        'unidade_id',
        'user_id',
        'entidade_id',
    ];

    public function receita()
    {
        return $this->belongsTo(ProdutoReceita::class, 'receita_id', 'id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id', 'id');
    }

    public function ingrediente()
    {
        return $this->belongsTo(Produto::class, 'ingrediente_id', 'id');
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
