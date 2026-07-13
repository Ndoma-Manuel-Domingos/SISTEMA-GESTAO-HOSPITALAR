<?php

namespace App\Models\CatalogoExame;

use Illuminate\Database\Eloquent\Model;

class FrequenciaPreInscricaoMedica extends Model
{
    // Especificando o nome da tabela
    protected $table = "frequencia_pre_inscricao_medica";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nome",
        "codigo",
    ];
}
