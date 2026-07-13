<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class CentroCusto extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'centros_custos';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'descricao',
        'user_id',
        'entidade_id',
    ];

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     
}
