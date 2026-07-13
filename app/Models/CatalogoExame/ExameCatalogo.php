<?php

namespace App\Models\CatalogoExame;

use Illuminate\Database\Eloquent\Model;

class ExameCatalogo extends Model
{
    // Especificando o nome da tabela
    protected $table = "exames_catalogo";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nome",
        "categoria",
        "codigo",
    ];

    public function parametros()
    {
        return $this->hasMany(ExameParametroCatalogo::class, 'exame_id', 'id');
    }
}
