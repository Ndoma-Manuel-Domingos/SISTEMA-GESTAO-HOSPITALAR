<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consulta extends Model
{
    use HasFactory;
    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'consultas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "data_consulta",
        "hora_consulta",
        "solicitacao_media_id",
        "paciente_id",
        "medico_id",
        "status",
        "pago",
        "total",
        "user_id",
        "entidade_id",
        "observacao",
        "avaliado",
        "diagnosticado",
        "atendimento_id", // encaminhamento
        "queixa_principal",
        "historia_doenca_actual",
        "historico_medico",
        "internamento_id",
        "exame_medico",
        "alergias_conhecidas",
        "anotacoes_gerais",
        "cids_id",
        "movito_agendamento",
    ];

    public function factura()
    {
        return $this->hasOne(Venda::class, 'consulta_id', 'id');
    }

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id', 'id');
    }

    public function cids()
    {
        return $this->belongsTo(CIDS::class, 'cids_id', 'id');
    }

    public function exames()
    {
        return $this->hasMany(Exame::class, 'consulta_id', 'id');
    }

    public function paciente()
    {
        return $this->belongsTo(Cliente::class, 'paciente_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(ConsultaItem::class, 'consulta_id', 'id');
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

    public function descricao_mes($string)
    {
        if ($string == "Nov") {
            return "Novembro";
        }
        if ($string == "Dec") {
            return "Dezembro";
        }
        if ($string == "Jan") {
            return "Janeiro";
        }
        if ($string == "Feb") {
            return "Fevereiro";
        }
        if ($string == "Mar") {
            return "Março";
        }
        if ($string == "Apr") {
            return "Abril";
        }
        if ($string == "May") {
            return "Maio";
        }
        if ($string == "Jun") {
            return "Junho";
        }
        if ($string == "Jul") {
            return "Julho";
        }
        if ($string == "Aug") {
            return "Agosto";
        }
        if ($string == "Sep") {
            return "Setembro";
        }
        if ($string == "Oct") {
            return "Outumbro";
        }
    }
}
