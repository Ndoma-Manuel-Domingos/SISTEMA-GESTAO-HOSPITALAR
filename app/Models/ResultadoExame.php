<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultadoExame extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'resultados_exames';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exame_id',
        'status',
        'referencia',
        'observacoes_resultado',
        'data_realizacao',
        'hora_realizacao',
        'user_id',
        'entidade_id',
    ];

    public function resultados_paramentros()
    {
        return $this->hasMany(ResultadoExameParamentro::class, 'resultado_id', 'id');
    }

    public function paramentros()
    {
        return $this->hasMany(ResultadoExameSubParamentro::class, 'resultado_id', 'id');
    }

    public function paramentros_imagem()
    {
        return $this->hasMany(ResultadoExameSubParamentroImagem::class, 'resultado_id', 'id');
    }

    public function exame()
    {
        return $this->belongsTo(Exame::class, 'exame_id', 'id');
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
