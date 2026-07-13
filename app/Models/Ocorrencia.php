<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



use DateTime;

class Ocorrencia extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'ocorrencias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'posto_id',
        'tipo_ocorrencia_id',
        'registrado_por_id',
        'numero',
        'descricao',
        'data_at',
        'hora_at',
        'user_id',
        'entidade_id',
    ];

    public function posto()
    {
        return $this->belongsTo(ContratoPosto::class, 'posto_id', 'id');
    }

    public function tipo_ocorrencia()
    {
        return $this->belongsTo(TipoOcorrencia::class, 'tipo_ocorrencia_id', 'id');
    }

    public function registrado_por()
    {
        return $this->belongsTo(User::class, 'registrado_por_id', 'id');
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
