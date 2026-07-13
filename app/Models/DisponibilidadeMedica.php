<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisponibilidadeMedica extends Model
{
    use HasFactory;

    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = "disponibilidades_medicas";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "medico_id",
        "data_inicio",
        "data_fim",
        "estado",
        "observacao",
        "user_id",
        "entidade_id",
    ];


    public function medico()
    {
        return $this->belongsTo(Medico::class, "medico_id", "id");
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
