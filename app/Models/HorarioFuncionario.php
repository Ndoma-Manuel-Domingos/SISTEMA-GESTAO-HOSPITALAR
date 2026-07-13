<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DateTime;

class HorarioFuncionario extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'horarios_funcionarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'funcionario_id',
        'dia_semana', // segunda, terça, etc
        'hora_inicio',
        'data_inicio',
        'hora_fim',
        'data_fim',
        'turno', // manhã, tarde, noite
        'posto_id',
        'tipo',
        'user_id',
        'entidade_id',
    ];

    public function posto()
    {
        return $this->belongsTo(ContratoPosto::class, 'posto_id', 'id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
