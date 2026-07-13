<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaHospitalarItem extends Model
{
    use SoftDeletes;

    protected $table = 'contas_hospitalares_items';

    protected $fillable = [
        'conta_hospitalar_id',
        'origem_id',
        'beneficiario_id',
        'cobertura_id',
        'quantidade',
        'preco_unitario',
        'desconto',
        'subtotal',
        'percentual_cobertura',
        'valor_seguradora',
        'valor_paciente',
        'cancelado',
        'user_id',
        'entidade_id'
    ];

    protected $casts = [
        'cancelado' => 'boolean',
        'quantidade' => 'decimal:2',
        'preco_unitario' => 'decimal:2',
        'desconto' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function conta()
    {
        return $this->belongsTo(ContaHospitalar::class, 'conta_hospitalar_id', 'id');
    }

    public function servico()
    {
        return $this->belongsTo(Produto::class, 'origem_id', 'id');
    }

    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
