<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Orcamento extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'orcamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status', //'rascunho', 'aprovado', 'rejeitado', 'finalizado'
        'codigo',
        'descricao',
        'tipo', // 'anual', 'trimestral', 'mensal', 'projeto'
        'data_inicio',
        'data_final',
        'versao',
        'responsavel_usuario_id',
        'exercicio_id',
        'periodo_id',
        'user_id',
        'entidade_id',
    ];

    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, "exercicio_id", "id");
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }
}
