<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class ContaCliente extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'saldo',
        'divida_corrente',
        'divida_vencida',
        'validade_cartao',
        'cliente_id',
        'conta_id',
        'user_id',
        'entidade_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function movimentos()
    {
        return $this->hasMany(MovimentoContaCliente::class, "conta_id", "id");
    }
}
