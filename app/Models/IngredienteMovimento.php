<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IngredienteMovimento extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'ingredientes_movimentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ingrediente_id',
        'tipo',
        'quantidade',
        'referencia',
        'observacao',
        'user_id',
        'entidade_id',
    ];

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class, 'ingrediente_id', 'id');
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
