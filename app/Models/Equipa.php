<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Equipa extends Model
{

    use HasFactory;
    
    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'equipas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'area_atuacao',
        'responsavel_id',
        'status', // 'activa','desactiva','suspensa','folga'
        'user_id',
        'entidade_id',
    ];
 
    public function responsavel()
    {
        return $this->belongsTo(Funcionario::class, 'responsavel_id', 'id');
    }
 
    public function membros()
    {
        return $this->hasMany(MembroEquipa::class, 'equipa_id', 'id');
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
