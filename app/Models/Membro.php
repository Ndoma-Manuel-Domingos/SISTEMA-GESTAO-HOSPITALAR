<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membro extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'membros';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'documento',
        'data_ingresso',
        'nacionalidade',
        'genero',
        'status',
        'photo',
        'profissao_id',
        'funcao_id',
        'user_id',
        'endereco'
    ];

    public function empresas()
    {
        return $this->hasMany(Entidade::class, 'membro_id', 'id');
    }

    public function profissao()
    {
        return $this->belongsTo(Profissao::class, 'profissao_id', 'id');
    }

    public function funcao()
    {
        return $this->belongsTo(Funcao::class, 'funcao_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
