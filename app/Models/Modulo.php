<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'modulos';

    protected $fillable = [
        'modulo',
        'tipo',
        'descricao',
    ];

    public function entidade()
    {
        return $this->belongsToMany(Entidade::class);
    }

    public function tipo_entidade()
    {
        return $this->belongsToMany(TipoEntidade::class);
    }
}
