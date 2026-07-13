<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Atendimento extends Model
{
    use HasFactory;
    use SoftDeletes;


    // Especificando o nome da tabela
    protected $table = 'atendimentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'status',
        'cliente_id',
        'code',
        'prioridade_id',
        'tipo_atendimento_id',
        'data_at',
        'profissional_id',
        'user_id',
        'entidade_id',
    ];


    public function contaHospitalar()
    {
        return $this->hasOne(ContaHospitalar::class, 'atendimento_id', 'id');
    }

    public function planoTratamento()
    {
        return $this->hasOne(PlanoTratamento::class, 'atendimento_id', 'id');
    }

    public function receita()
    {
        return $this->hasOne(ReceitaMedica::class, 'atendimento_id', 'id');
    }

    public function receitas()
    {
        return $this->hasMany(ReceitaMedica::class, 'atendimento_id', 'id');
    }

    public function internamento()
    {
        return $this->hasOne(Internamento::class, 'atendimento_id', 'id');
    }

    public function exames()
    {
        return $this->hasMany(Exame::class, 'atendimento_id', 'id');
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'atendimento_id', 'id');
    }

    public function triagem()
    {
        return $this->hasOne(FichaTriagem::class, 'atendimento_id', 'id');
    }

    public function paciente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    public function prioridade()
    {
        return $this->belongsTo(Prioridade::class, 'prioridade_id', 'id');
    }
    public function tipo()
    {
        return $this->belongsTo(TipoAtendimento::class, 'tipo_atendimento_id', 'id');
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
