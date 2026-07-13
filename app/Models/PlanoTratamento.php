<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class PlanoTratamento extends Model
{
    use HasFactory;
    use SoftDeletes;


    // Especificando o nome da tabela
    protected $table = 'planos_tratamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status', // 'Ativo','Suspenso','finalizado', 'cancelado
        'paciente_id',
        'equipa_id',
        'atendimento_id', //origem do internamento
        'titulo',
        'descricao',
        'tipo',
        'objectivo',
        'observacoes_finais',
        'data_finalizacao',
        'data_inicio',
        'data_final',
        'duracao_semanas',
        'frequencia',
        'motivo_cancelamento',
        'data_cancelamento',
        'motivo_suspesao',
        'data_suspesao',
        'user_id',
        'entidade_id',
    ];


    public function factura()
    {
        return $this->hasOne(Venda::class, 'tratamento_id', 'id');
    }

    public function sessoes_tratamento()
    {
        return $this->hasMany(SessaoTratamento::class, 'plano_atendimento_id', 'id');
    }

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id', 'id');
    }

    public function tipo_atendimento()
    {
        return $this->belongsTo(TipoAtendimento::class, 'atendimento_id', 'id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    public function paciente()
    {
        return $this->belongsTo(Cliente::class, 'paciente_id', 'id');
    }

    public function equipa()
    {
        return $this->belongsTo(Equipa::class, 'equipa_id', 'id');
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
