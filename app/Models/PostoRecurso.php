<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class PostoRecurso extends Model
{

    use HasFactory;
    use SoftDeletes;
        
    // Especificando o nome da tabela
    protected $table = 'postos_recursos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'recurso_id',
        'descricao',
        'posto_id',
        'entidade_id',
    ];

    public function postos()
    {
        return $this->belongsTo(ContratoPosto::class, 'posto_id', 'id');
    }

    public function recurso()
    {
        return $this->belongsTo(EquipamentoActivo::class, 'recurso_id', 'id');
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
