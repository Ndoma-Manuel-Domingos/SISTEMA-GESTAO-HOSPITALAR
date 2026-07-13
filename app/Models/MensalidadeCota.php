<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MensalidadeCota extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'mensalidades_cota';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'membro_id',
        'mes',
        'ano',
        'valor_original',
        'multa',
        'juros',
        'valor_total',
        'valor_pago',
        'saldo_devedor',
        'data_vencimento',
        'data_pagamento',
        'dias_atraso',
        'status'
    ];

    public function membro()
    {
        return $this->belongsTo(Membro::class, 'membro_id', 'id');
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'mensalidade_id', 'id');
    }
}
