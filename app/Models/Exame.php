<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exame extends Model
{

    use HasFactory;

    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = "exames";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "data_exame",
        "hora_exame",
        "paciente_id",
        "consulta_id",
        "internamento_id",
        "atendimento_id",
        "prioridade_id",
        "profissional_saude_id",
        "solicitante_id",
        "solicitante_type",
        "status",
        "pago",
        "total",
        "user_id",
        "entidade_id",
        "observacao",
    ];

    public function resultado()
    {
        return $this->hasOne(ResultadoExame::class, 'exame_id', 'id');
    }

    public function atendimento()
    {
        return $this->belongsTo(Consulta::class, "atendimento_id", "id");
    }

    public function factura()
    {
        return $this->hasOne(Venda::class, 'exame_id', 'id');
    }

    public function internamento()
    {
        return $this->belongsTo(Consulta::class, "internamento_id", "id");
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, "consulta_id", "id");
    }

    public function profissional()
    {
        return $this->belongsTo(User::class, "profissional_saude_id", "id");
    }

    public function prioridade()
    {
        return $this->belongsTo(Prioridade::class, "prioridade_id", "id");
    }

    public function solicitante_paciente()
    {
        return $this->belongsTo(Cliente::class, "solicitante_id", "id");
    }

    public function solicitante_medico()
    {
        return $this->belongsTo(User::class, "solicitante_id", "id");
    }

    public function paciente()
    {
        return $this->belongsTo(Cliente::class, "paciente_id", "id");
    }

    public function items()
    {
        return $this->hasMany(ExameItem::class, "exame_id", "id");
    }

    public function profissional_saude()
    {
        return $this->belongsTo(Medico::class, "profissional_saude_id", "id");
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
