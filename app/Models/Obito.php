<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Obito extends Model
{

    use HasFactory;

    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'obitos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "paciente_id",
        "medico_id",
        "atendimento_id", // encaminhamento
        "data_obito",
        "hora_obito",
        "local_obito",
        "tipo_obito",
        "causa_obito",
        "comunicacao_obito",
        "documento_declaracao",
        "status",
        "pago",
        "total",
        "user_id",
        "entidade_id",
    ];

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id', 'id');
    }

    public function paciente()
    {
        return $this->belongsTo(Cliente::class, 'paciente_id', 'id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id', 'id');
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
