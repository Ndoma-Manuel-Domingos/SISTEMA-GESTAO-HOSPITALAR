<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class FacturaEncomendaFornecedorPagamento extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'factura_encomenda_fornecedores_pagamentos';

    protected $fillable = [
        'factura_id',
        'data_pagamento',
        'forma_pagamento_id',
        'observacao',
        'valor_pago',
        'descricao',
        'user_id',
        'entidade_id',
    ];

    public function forma_pagamento()
    {
        return $this->belongsTo(TipoPagamento::class, 'forma_pagamento_id', 'id');
    }

    public function factura()
    {
        return $this->belongsTo(FacturaEncomendaFornecedor::class, 'factura_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
