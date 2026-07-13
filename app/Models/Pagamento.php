<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pagamento extends Model
{

    use HasFactory;

    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'pagamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'membro_id',
        'mensalidade_id',
        'valor_pago',
        'metodo_pagamento',
        'referencia',
        'observacao',
        'data_pagamento'
    ];

    public function membro()
    {
        return $this->belongsTo(Membro::class, 'membro_id', 'id');
    }

    public function mensalidade()
    {
        return $this->belongsTo(MensalidadeCota::class, 'mensalidade_id', 'id');
    }
}
