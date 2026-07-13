<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacturaSeguradora extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'facturas_seguradoras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'seguradora_id',
        'mes',
        'ano',
        'subtotal',
        'desconto',
        'acrescimo',
        'total',
        'valor_pago',
        'saldo',
        'status',
        'observacao',
        'data_emissao',
        'data_vencimento',
        'user_id',
        'entidade_id'
    ];

    protected $casts = [
        'data_emissao'   => 'datetime',
        'data_vencimento' => 'datetime'
    ];

    public function seguradora()
    {
        return $this->belongsTo(Seguradora::class);
    }

    public function contas()
    {
        return $this->belongsToMany(ContaHospitalar::class, 'factura_seguradora_contas')->using(FacturaSeguradoraConta::class)->withPivot([
            'valor',
            'desconto',
            'acrescimo',
            'subtotal',
            'total'
        ])
            ->withTimestamps();
    }

    public function itens()
    {
        return $this->hasMany(FacturaSeguradoraConta::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
