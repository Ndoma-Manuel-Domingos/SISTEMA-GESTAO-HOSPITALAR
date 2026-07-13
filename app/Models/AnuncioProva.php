<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class AnuncioProva extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'anuncios_provas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prova_id',
        'turma_id',
        'formador_id',
        'user_id',
        'entidade_id',
    ];

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
