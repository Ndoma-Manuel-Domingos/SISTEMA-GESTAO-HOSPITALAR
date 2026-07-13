<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class MorgueLiberacao extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'morgue_liberacoes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'morgue_registro_id',
        'data_liberacao',
        'hora_liberacao',
        'nome_responsavel_retirada',
        'documento_responsavel',
        'relacionamento',
        'empresa_funeraria',
        'observacoes',
        'entidade_id',
        'user_id',
    ];
  
    public function morgue()
    {
        return $this->belongsTo(Morgue::class, 'morgue_registro_id', 'id');
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
