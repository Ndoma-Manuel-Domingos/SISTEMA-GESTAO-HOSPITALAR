<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguradoraPlanoCobertura extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'seguradoras_planos_coberturas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plano_id',
        'servico_id',
        'limite',
        'copagamento',
        'percentual',
        'status',
        'observacoes',
        'user_id',
        'entidade_id',
    ];

    protected $casts = [
        'limite' => 'decimal:2',
        'status' => 'boolean'
    ];

    public function plano()
    {
        return $this->belongsTo(SeguradoraPlano::class, 'plano_id', 'id');
    }

    public function servico()
    {
        return $this->belongsTo(Produto::class, 'servico_id', 'id');
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
