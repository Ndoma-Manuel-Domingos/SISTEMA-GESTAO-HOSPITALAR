<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DateTime;

class FacturaSeguradoraConta extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $table = 'factura_seguradora_contas';

    protected $fillable = [
        'factura_seguradora_id',
        'conta_hospitalar_id',
        'valor',
        'desconto',
        'acrescimo',
        'subtotal',
        'total',
        'user_id',
        'entidade_id'
    ];

    public function factura()
    {
        return $this->belongsTo(FacturaSeguradora::class, 'factura_seguradora_id');
    }

    public function conta()
    {
        return $this->belongsTo(ContaHospitalar::class, 'conta_hospitalar_id');
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
