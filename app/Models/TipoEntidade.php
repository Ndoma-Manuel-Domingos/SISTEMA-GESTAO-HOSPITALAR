<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class TipoEntidade extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'tipo_entidades';

    protected $fillable = [
        'tipo',
        'descricao',
        'status',
        'sigla',
    ];

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class);
    }

}
