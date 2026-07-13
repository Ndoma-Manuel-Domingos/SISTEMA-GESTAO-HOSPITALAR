<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class ModuloEntidade extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'modulo_entidade';

    protected $fillable = [
        'entidade_id',
        'modulo_id',
    ];

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class);
    }

}
