<?php

namespace App\Models\CatalogoExame;

use Illuminate\Database\Eloquent\Model;

class ExameSubParametroCatalogo extends Model
{
    // Especificando o nome da tabela
    protected $table = "exames_catalogo_subparametros";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exame_id',
        'parametro_id',
        'tipo',
        'nome',
        'unidade',
        'valor_referencia',
        'valor_minimo',
        'valor_maximo',
        'opcoes',
        'texto_sim',
        'texto_nao',
        'extensoes_permitidas',
        'permitir_futuro',
        'permitir_passado',
        'tamanho_maximo',
    ];
}
