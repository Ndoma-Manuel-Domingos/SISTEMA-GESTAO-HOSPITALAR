<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetornoInvestimento extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'retornos_investimento';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'investimento_id',
        'motante',
        'data_retorno',
        'tipo',
        'descricao',
        'user_id',
        'entidade_id'
    ];

    protected $casts = [
        'data_retorno' => 'date'
    ];


    public function investimento()
    {
        return $this->belongsTo(Investimento::class, 'investimento_id', 'id');
    }
}
