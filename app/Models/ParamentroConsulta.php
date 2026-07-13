<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParamentroConsulta extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'paramentros_consultas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consulta_id',
        'nome',
        'codigo',
        'tipo',
        'unidade',
        'valor_referencia',
        'valor_minimo',
        'valor_maximo',
        'opcoes',
        'texto_sim',
        'texto_nao',
                
        'tamanho_maximo',
        'valor_padrao',
        
        'permitir_passado',
        'permitir_futuro',
        
        'linhas',
        
        'extensoes_permitidas',
        'tamanho_max_arquivo',
        
        'ordem',
        'activo',
        'obrigatorio',
        'user_id',
        'entidade_id',
    ];
    
    public function consulta()
    {
        return $this->belongsTo(Produto::class, 'consulta_id', 'id');
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
