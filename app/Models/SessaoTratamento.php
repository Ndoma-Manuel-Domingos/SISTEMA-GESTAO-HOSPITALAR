<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class SessaoTratamento extends Model
{
    use HasFactory;
    use SoftDeletes;


    // Especificando o nome da tabela
    protected $table = 'sessao_tratamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plano_atendimento_id', // 'Ativo','Suspenso','finalizado', 'cancelado
        'observacoes',
        'status',
        'data_at',
        'user_id',
        'entidade_id',
    ];

    public function plano_tratamento()
    {
        return $this->belongsTo(PlanoTratamento::class, 'plano_atendimento_id', 'id');
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
