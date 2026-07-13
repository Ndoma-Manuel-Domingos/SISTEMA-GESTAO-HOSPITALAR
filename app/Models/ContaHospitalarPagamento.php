<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaHospitalarPagamento extends Model
{
    use SoftDeletes;

    protected $table = 'contas_hospitalares_pagamentos';

    protected $fillable = [
        'conta_hospitalar_id',
        'setor',
        'valor',
        'troco',
        'saldo_anterior',
        'saldo_restante',
        'escopo_pagamento',
        'tipo',
        'forma_pagamento',
        'forma_pagamento_id',
        'referencia',
        'observacao',
        'user_id',
        'entidade_id'
    ];

    protected $casts = [
        'valor' => 'decimal:2'
    ];

    public function paciente()
    {
        return $this->belongsTo(ContaHospitalar::class, 'conta_hospitalar_id', 'id');
    }

    // public function forma_pagamento()
    // {
    //     return $this->belongsTo(TipoPagamento::class, 'forma_pagamento_id', 'id');
    // }

    public function recebido_por()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
