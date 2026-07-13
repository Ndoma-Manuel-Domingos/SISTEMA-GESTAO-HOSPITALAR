<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanoInternamento extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'plano_internamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'internamento_id',
        'medicamento', // 'Ativo','Alta','Obito'
        'dose',
        'via',
        'frequencia',
        'duracao', //solicitante do internamento
        'entidade_id',
    ];

    public function internamento()
    {
        return $this->belongsTo(Internamento::class, 'internamento_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
