<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quarto extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'quartos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'solicitar_ocupacao',
        'capacidade',
        'tipo_id',
        'andar_id',
        'hora_entrada',
        'hora_saida',
        'data_inicio',
        'data_final',
        'descricao',
        'status',
        'code',
        'entidade_id',
        'user_id',
    ];


    public function leitos()
    {
        return $this->hasMany(Leito::class, 'quarto_id', 'id');
    }

    public function quartos()
    {
        return $this->hasMany(QuartoTarefario::class, 'quarto_id', 'id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoQuarto::class, 'tipo_id', 'id');
    }

    public function andar()
    {
        return $this->belongsTo(Andar::class, 'andar_id', 'id');
    }
}
