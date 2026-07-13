<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultadoConsulta extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'resultados_consultas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consulta_id',
        'status',
        'referencia',
        'observacoes_resultado',
        'data_realizacao',
        'hora_realizacao',
        'user_id',
        'entidade_id',
    ];
    
    public function paramentros()
    {
        return $this->hasMany(ResultadoConsultaParamentro::class, 'resultado_id', 'id');
    }
    
    public function paramentros_imagens()
    {
        return $this->hasMany(ResultadoConsultaParamentroImagem::class, 'resultado_id', 'id');
    }
    
    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id', 'id');
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
