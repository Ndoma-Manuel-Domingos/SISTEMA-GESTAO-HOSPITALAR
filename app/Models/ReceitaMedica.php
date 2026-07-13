<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceitaMedica extends Model
{
    use HasFactory;
    use SoftDeletes;


    // Especificando o nome da tabela
    protected $table = 'receitas_medicas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'atendimento_id',
        'observacoes',
        'user_id',
        'entidade_id',
    ];

    public function items()
    {
        return $this->hasMany(ReceitaMedicaItem::class, 'receita_id', 'id');
    }


    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id', 'id');
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
