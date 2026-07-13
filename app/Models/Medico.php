<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;

class Medico extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'medicos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero_cedula',
        'entidade_registradora',
        'provincia_registro',

        'data_emissao_cedula',
        'data_validade_cedula',
        'status_profissional',
        'tipo',
        'user_id',
        'funcionario_id',
        'especialidade_id',
        'entidade_id',
    ];

    public function disponibilidades()
    {
        return $this->hasMany(DisponibilidadeMedica::class, 'medico_id', 'id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id', 'id');
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidade_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_registro', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
