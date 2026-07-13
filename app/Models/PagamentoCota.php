<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagamentoCota extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'pagamentos_cotas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'membro_id',
        'mensalidade_id',
        'valor_pago',
        'metodo_pagamento',
        'data_pagamento',
        'comprovativo',
        'observacoes',
        'banco_origem',
        'referencia',
        'status',
        'user_id',
    ];

    public function mensalidade()
    {
        return $this->belongsTo(MensalidadeCota::class, 'mensalidade_id', 'id');
    }

    public function membro()
    {
        return $this->belongsTo(Membro::class, 'membro_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }
}
