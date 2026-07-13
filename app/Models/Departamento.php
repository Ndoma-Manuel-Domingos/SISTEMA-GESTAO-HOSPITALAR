<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Departamento extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'departamentos';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'sigla',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'departamento_id', 'id');
    }

    public function produto()
    {
        return $this->hasOne(Produto::class);
    }

}
