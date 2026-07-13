<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class ConsultaItem extends Model
{

    use HasFactory;
    
    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'consultas_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produto_id',
        'consulta_id',
        'valor',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function paramentos_consultas()
    {
        return $this->hasMany(ResultadoConsultaParamentro::class, 'item_consulta_id', 'id');
    }

    public function paramentos_consultas_imagem()
    {
        return $this->hasMany(ResultadoConsultaParamentroImagem::class, 'item_consulta_id', 'id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id', 'id');
    }


    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
