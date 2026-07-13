<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Morgue extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'morgues';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'obito_id',
        'status',
        'responsavel_recebimento_id',
        'camara_id',
        'gaveta_id',
        'data_entrada_morgue',
        'hora_entrada_morgue',
        'data_liberacao',
        'hora_liberacao',
        'temperatura_armazenamento',
        'observacoes_iniciais',
        'hora_liberacao',
        'status', // ativa, inativa, manutencao
        'entidade_id',
        'user_id',
    ];
  
    public function liberacao()
    {
        return $this->hasOne(MorgueLiberacao::class, 'morgue_registro_id', 'id');
    }
  
  
    public function obito()
    {
        return $this->belongsTo(Obito::class, 'obito_id', 'id');
    }
  
    public function gaveta()
    {
        return $this->belongsTo(Gaveta::class, 'gaveta_id', 'id');
    }
  
    public function camara()
    {
        return $this->belongsTo(Camara::class, 'camara_id', 'id');
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
