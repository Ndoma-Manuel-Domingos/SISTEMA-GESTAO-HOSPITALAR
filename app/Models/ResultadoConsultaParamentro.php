<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultadoConsultaParamentro extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'resultado_consultas_parametros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'resultado_id',
        'parametro_id',
        'valor',
        'item_consulta_id',
        'user_id',
        'entidade_id',
    ];
        
    public function item_consulta()
    {
        return $this->belongsTo(ConsultaItem::class, 'item_consulta_id', 'id');
    }
    
    public function resultado()
    {
        return $this->belongsTo(ResultadoConsulta::class, 'resultado_id', 'id');
    }
    
    public function paramentro()
    {
        return $this->belongsTo(ParamentroConsulta::class, 'parametro_id', 'id');
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
