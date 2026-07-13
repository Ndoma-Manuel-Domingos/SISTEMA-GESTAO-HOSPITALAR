<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoReserva extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'tipos_reservas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'nome',
        'status',
        'entidade_id',
        'user_id',
    ];
}
