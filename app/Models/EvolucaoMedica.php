<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class EvolucaoMedica extends Model
{

    use HasFactory;
    
    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'evolucoes_medicas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'internamento_id',
        'status', // 'activa','desactiva','suspensa','folga'
        'profissional_id',
        'tipo',
        'data_evolucao',
        'observacoes',
        'user_id',
        'entidade_id',
    ];
 
    public function profissional()
    {
        return $this->hasMany(Funcionario::class, 'profissional_id', 'id');
    }
 
    public function internamento()
    {
        return $this->hasMany(Internamento::class, 'internamento_id', 'id');
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
