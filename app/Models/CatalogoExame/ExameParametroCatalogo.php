<?php

namespace App\Models\CatalogoExame;

use Illuminate\Database\Eloquent\Model;

class ExameParametroCatalogo extends Model
{
    // Especificando o nome da tabela
    protected $table = "exames_catalogo_parametros";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "exame_id",
        "nome",
        "ordem",
    ];


    public function subparametros()
    {
        return $this->hasMany(ExameSubParametroCatalogo::class, 'parametro_id', 'id');
    }
}
