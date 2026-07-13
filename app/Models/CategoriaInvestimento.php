<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaInvestimento extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'categorias_investimento';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'cor',
        'icone',
        'user_id',
        'entidade_id'
    ];
}
