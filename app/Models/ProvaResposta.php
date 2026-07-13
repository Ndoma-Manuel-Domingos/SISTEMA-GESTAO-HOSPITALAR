<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class ProvaResposta extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'provas_respostas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prova_id',
        'questao_id',
        'resposta_aluno',
        'resposta_certa',
        'status',
        'user_id',
        'entidade_id',
    ];
    
    public function questoes()
    {
        return $this->hasMany(ProvaQuestao::class, 'prova_id', 'id');
    }
    
    public function modulo()
    {
        return $this->belongsTo(CursoModulo::class, 'modulo_id', 'id');
    }
    
    public function prova()
    {
        return $this->belongsTo(Prova::class, 'prova_id', 'id');
    }
    
    public function questao()
    {
        return $this->belongsTo(ProvaQuestao::class, 'questao_id', 'id');
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
