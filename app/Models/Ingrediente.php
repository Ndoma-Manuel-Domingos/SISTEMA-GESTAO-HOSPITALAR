<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingrediente extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'ingredientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'unidade',
        'quantidade_stock',
        'quantidade_minima',
        'preco_custo_unitario',
        'user_id',
        'entidade_id',
    ];
    
    public function movimentos()
    {
        return $this->hasMany(IngredienteMovimento::class, 'ingrediente_id', 'id');
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
