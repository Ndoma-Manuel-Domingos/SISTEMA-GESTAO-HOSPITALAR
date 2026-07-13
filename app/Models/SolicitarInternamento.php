<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class SolicitarInternamento extends Model
{
    use HasFactory;
    use SoftDeletes;


    // Especificando o nome da tabela
    protected $table = 'solicitacoes_internacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'status',
        'status',
        'paciente_id',
        'medico_id',
        'prioridade_id',
        'unidate_desejada',
        'justificativo',
        'tipo_internamento',
        'data_at',
        'user_id',
        'entidade_id',
    ];

    public function paciente()
    {
        return $this->belongsTo(Cliente::class, 'paciente_id', 'id');
    }

    public function prioridade()
    {
        return $this->belongsTo(Prioridade::class, 'prioridade_id', 'id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
