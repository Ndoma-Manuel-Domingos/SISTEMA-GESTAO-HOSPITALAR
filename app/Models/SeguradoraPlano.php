<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguradoraPlano extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'seguradoras_planos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seguradora_id',
        'codigo',
        'nome',
        'tipo',
        'descricao',
        'percentual_cobertura',
        'percentual_coparticipacao',
        'limite_anual',
        'limite_por_atendimento',
        'dias_carencia',
        'necessita_autorizacao',
        'ativo',
        'user_id',
        'entidade_id',
    ];

    protected $casts = [
        'percentual_cobertura' => 'decimal:2',
        'percentual_coparticipacao' => 'decimal:2',
        'limite_anual' => 'decimal:2',
        'limite_por_atendimento' => 'decimal:2',
        'necessita_autorizacao' => 'boolean',
        'ativo' => 'boolean'
    ];


    public function seguradora()
    {
        return $this->belongsTo(Seguradora::class, 'seguradora_id', 'id');
    }

    public function coberturas()
    {
        return $this->hasMany(SeguradoraPlanoCobertura::class, 'plano_id', 'id');
    }

    public function beneficiarios()
    {
        return $this->hasMany(SeguradoraPlanoBeneficiador::class, 'plano_id', 'id');
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
