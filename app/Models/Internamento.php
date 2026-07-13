<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Internamento extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'internamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'status', // 'Ativo','Alta','Obito'
        'paciente_id',
        'leito_id',
        'equipa_id',
        'medico_id', //solicitante do internamento
        'atendimento_id', //origem do internamento
        'motivo',
        'diagnostico_inicial',
        'data_internacao',
        'data_alta',
        'resumo_alta',
        'resumo_obito',
        'resumo_transferencia',
        'user_id',
        'entidade_id',
    ];

    public function factura()
    {
        return $this->hasOne(Venda::class, 'internamento_id', 'id');
    }

    public function plano_internamento()
    {
        return $this->hasMany(PlanoInternamento::class, 'internamento_id', 'id');
    }

    public function evolucao_medica()
    {
        return $this->hasMany(EvolucaoMedica::class, 'internamento_id', 'id');
    }

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id', 'id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id', 'id');
    }

    public function tipo_atendimento()
    {
        return $this->belongsTo(TipoAtendimento::class, 'atendimento_id', 'id');
    }

    public function leito()
    {
        return $this->belongsTo(Leito::class, 'leito_id', 'id');
    }
    public function paciente()
    {
        return $this->belongsTo(Cliente::class, 'paciente_id', 'id');
    }
    public function equipa()
    {
        return $this->belongsTo(Equipa::class, 'equipa_id', 'id');
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
