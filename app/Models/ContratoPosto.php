<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




use DateTime;

class ContratoPosto extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'contratos_postos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'equipa_id',
        'contrato_id',
        'uso_armas',
        'nome',
        'endereco',
        'contacto_posto',
        'representante_posto',
        'longitude',
        'coordenadas',
        'latitude',
        'tipo_posto_id',
        'instrucoes_especiais',
        'horario_permitido',
        'user_id',
        'entidade_id',
    ];

    public function recursos()
    {
        return $this->hasMany(PostoRecurso::class, 'posto_id', 'id');
    }

    public function escalas()
    {
        return $this->hasMany(HorarioFuncionario::class, 'posto_id', 'id');
    }
    
    public function equipa()
    {
        return $this->belongsTo(Equipa::class, 'equipa_id', 'id');
    }

    public function contrato()
    {
        return $this->belongsTo(ClienteContrato::class, 'contrato_id', 'id');
    }

    public function tipo_posto()
    {
        return $this->belongsTo(TipoPosto::class, 'tipo_posto_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    
    function diasRestantes($dataInicio, $dataFim)
    {
        // Converter as datas para instâncias do Carbon
        $inicio = Carbon::parse($dataInicio);
        $fim = Carbon::parse($dataFim);
    
        // Calcular diferença em dias
        $dias = $fim->diffInDays($inicio, false); // false para retornar negativo se a data final for menor
    
        return $dias;
    }

}
