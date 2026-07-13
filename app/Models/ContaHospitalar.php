<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaHospitalar extends Model
{
    use SoftDeletes;

    protected $table = 'contas_hospitalares';

    protected $fillable = [
        'paciente_id',
        'atendimento_id',
        'plano_id',
        'numero',
        'status',
        'subtotal',
        'desconto',
        'acrescimo',
        'total',
        'valor_pago',

        'valor_paciente',
        'valor_seguradora',

        'valor_pago_paciente',
        'valor_pago_seguradora',

        'saldo_paciente',
        'saldo_seguradora',

        'fechada_em',
        'fechada_por',

        'saldo',
        'observacao',
        'user_id',
        'entidade_id'
    ];

    protected $casts = [
        'subtotal'    => 'decimal:2',
        'desconto'    => 'decimal:2',
        'acrescimo'   => 'decimal:2',
        'total'       => 'decimal:2',
        'valor_pago'  => 'decimal:2',
        'valor_pago_paciente'  => 'decimal:2',
        'valor_pago_seguradora'  => 'decimal:2',
        'valor_seguradora'  => 'decimal:2',
        'saldo_paciente'  => 'decimal:2',
        'saldo_seguradora'  => 'decimal:2',
        'valor_paciente'  => 'decimal:2',
        'saldo'       => 'decimal:2',
    ];

    public function plano()
    {
        return $this->belongsTo(SeguradoraPlano::class, 'plano_id', 'id');
    }

    public function paciente()
    {
        return $this->belongsTo(Cliente::class, 'paciente_id', 'id');
    }

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id', 'id');
    }

    public function itens()
    {
        return $this->hasMany(ContaHospitalarItem::class, 'conta_hospitalar_id', 'id');
    }

    public function facturasSeguradora()
    {
        return $this->belongsToMany(FacturaSeguradora::class, 'factura_seguradora_contas')
            ->using(FacturaSeguradoraConta::class)
            ->withPivot([
                'valor',
                'desconto',
                'acrescimo',
                'subtotal',
                'total'
            ])
            ->withTimestamps();
    }

    public function pagamentos()
    {
        return $this->hasMany(ContaHospitalarPagamento::class, 'conta_hospitalar_id', 'id');
    }

    public function movimentos()
    {
        return $this->hasMany(ContaHospitalarMovimento::class, 'conta_hospitalar_id', 'id');
    }

    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function fechadaPor()
    {
        return $this->belongsTo(User::class, 'fechada_por', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
