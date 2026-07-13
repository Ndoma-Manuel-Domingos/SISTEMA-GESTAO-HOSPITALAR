<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultadoExameParamentro extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'resultado_exames_parametros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'resultado_id',
        'exame_id',
        'nome',
        'ordem',
        'user_id',
        'entidade_id',
    ];

    public function resultado()
    {
        return $this->belongsTo(ResultadoExame::class, 'resultado_id', 'id');
    }

    public function exame()
    {
        return $this->belongsTo(Produto::class, 'exame_id', 'id');
    }

    public function resultadosubparamentros()
    {
        return $this->hasMany(ResultadoExameSubParamentro::class, 'parametro_id', 'id');
    }

    public function resultadosubparamentrosImagem()
    {
        return $this->hasMany(ResultadoExameSubParamentroImagem::class, 'parametro_id', 'id');
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
