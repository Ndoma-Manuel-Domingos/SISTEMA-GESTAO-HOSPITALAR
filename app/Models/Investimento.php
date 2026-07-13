<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investimento extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'investimentos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'categoria_id',
        'tipo',
        'valor_investido',
        'valor_atual',
        'retorno_esperado',
        'taxa_anual',
        'nivel_risco',
        'data_inicio',
        'data_final',
        'status',
        'descricao',
        'user_id',
        'entidade_id'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_final' => 'date'
    ];


    public function categoria()
    {
        return $this->belongsTo(CategoriaInvestimento::class, 'categoria_id', 'id');
    }

    public function returnos()
    {
        return $this->hasMany(RetornoInvestimento::class, 'id', 'investimento_id');
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
