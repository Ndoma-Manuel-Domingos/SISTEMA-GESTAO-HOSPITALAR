<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Gaveta extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'gavetas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'nome',
        'status',
        'camara_id',
        'entidade_id',
        'user_id',
    ];
    
    public function morgue()
    {
        return $this->hasOne(Morgue::class, 'gaveta_id', 'id');
    }
    
    public function camara()
    {
        return $this->belongsTo(Camara::class, 'camara_id', 'id');
    }
}
