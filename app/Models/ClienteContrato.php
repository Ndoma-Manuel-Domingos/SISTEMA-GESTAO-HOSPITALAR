<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




use DateTime;

class ClienteContrato extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'clientes_contratos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cliente_id',
        'codigo_contrato',
        'descricao',
        'status', //'Pendente','Activo','Terminado','Cancelado'
        'data_inicio',
        'data_final',
        'valor_mensal',
        'forma_pagamento_id',
        'user_id',
        'entidade_id',
    ];

    public function postos()
    {
        return $this->hasMany(ContratoPosto::class, 'contrato_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function forma_pagamento()
    {
        return $this->belongsTo(TipoPagamento::class, 'forma_pagamento_id', 'id');
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
