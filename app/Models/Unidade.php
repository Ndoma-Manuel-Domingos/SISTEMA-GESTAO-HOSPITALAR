<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'unidades_medida';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'sigla',
        'tipo',
        'fator_conversao'
    ];
}
