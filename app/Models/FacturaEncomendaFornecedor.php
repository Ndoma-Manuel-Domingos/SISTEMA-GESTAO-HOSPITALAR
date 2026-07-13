<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class FacturaEncomendaFornecedor extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'factura_encomenda_fornecedors';

    protected $fillable = [
        'fornecedor_id',
        'encomenda_id',
        'user_id',
        'desconto',
        'desconto_valor',
        'valor_factura',
        'valor_pago',
        'total_pago',
        'valor_divida',
        'data_factura',
        'data_vencimento',
        'data_pagamento',
        'observacao',
        'referenciante',
        'status',
        'factura',
        'status2',
        'status3',
        'entidade_id',
    ];

    public function pagamentos()
    {
        return $this->hasMany(FacturaEncomendaFornecedorPagamento::class, 'factura_id', 'id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedore::class, 'fornecedor_id', 'id');
    }

    public function encomenda()
    {
        return $this->belongsTo(EncomendaFornecedore::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
