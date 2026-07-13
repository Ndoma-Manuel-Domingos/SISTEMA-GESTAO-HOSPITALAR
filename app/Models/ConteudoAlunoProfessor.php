<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class ConteudoAlunoProfessor extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'formador_alunos_conteudos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'status',
        'data_inicio',
        'data_final',
        'turma_id',
        'formador_id',
        'user_id',
        'entidade_id',
    ];

    public function verificar_envio($id_aluno, $id_conteudo)
    {
        $contuedo = AlunoConteudo::where('aluno_id', $id_aluno)->where('conteudo_id', $id_conteudo)->get();
        
        if(count($contuedo) >= 1){
            return true;
        }else {
            return false;
        }
    
    }

    public function prova()
    {
        return $this->belongsTo(Prova::class, 'prova_id', 'id');
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turma_id', 'id');
    }

    public function formador()
    {
        return $this->belongsTo(Formador::class, 'formador_id', 'id');
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
