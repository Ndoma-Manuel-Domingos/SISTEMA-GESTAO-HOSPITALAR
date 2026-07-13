<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FichaTriagem extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'triagens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consulta_id',
        'atendimento_id',
        'paciente_id',
        'profissional_id',
        'prioridade_id',
        'tipo_atendimento_id',
        'pressao',
        'queixa_principal',
        'imc_classificacao',
        'peso',
        'altura',
        'temperatura',
        'freq_respiratoria',
        'freq_cardiaca',
        'imc',
        
        'estado_consciencia',
        'pressao_diatolica',
        'saturacao_oxigenio',
        'escala_dor',
        'circunferencia_abdominal',
        'glicemia_capilar',
        'gravidez',
        
        'observacoes',
        'user_id',
        'internamento_id',
        'status',
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

    public function profissional()
    {
        return $this->belongsTo(Medico::class, 'profissional_id', 'id');
    }

    public function tipo_atendimento()
    {
        return $this->belongsTo(TipoAtendimento::class, 'tipo_atendimento_id', 'id');
    }

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
