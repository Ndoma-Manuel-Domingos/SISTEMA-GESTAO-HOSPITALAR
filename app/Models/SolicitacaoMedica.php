<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoMedica extends Model
{
    use HasFactory;
    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'solicitacoes_medica';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "solicitacao",
        "paciente_id",
        "medico_id",
        "status", // 'pendente','em_analise','agendado','executado','cancelado'
        "tipo", // 'consulta','exame'
        "user_id",
        "entidade_id",
        "justificativa",
        "prioridade_id",
        "atendimento_id",
    ];

    public function items()
    {
        return $this->hasMany(SolicitacaoMedicaItem::class, 'solicitacao_medica_id', 'id');
    }

    public function prioridade()
    {
        return $this->belongsTo(Prioridade::class, 'prioridade_id', 'id');
    }

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
