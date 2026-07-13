<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguradoraPlanoBeneficiador extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'seguradoras_planos_beneficiadores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plano_id',
        'beneficiario_id',
        'numero_cartao',
        'matricula',
        'data_inicio',
        'data_fim',
        'limite',
        'status',
        'observacoes',
        'user_id',
        'entidade_id',
    ];

    protected $casts = [
        'limite' => 'decimal:2',
    ];


    public function plano()
    {
        return $this->belongsTo(SeguradoraPlano::class, 'plano_id', 'id');
    }


    public function beneficiario()
    {
        return $this->belongsTo(Cliente::class, 'beneficiario_id', 'id');
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
