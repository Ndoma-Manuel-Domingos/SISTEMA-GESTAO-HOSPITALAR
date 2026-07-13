<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultadoExameSubParamentro extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'resultado_exames_subparametros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'resultado_id',
        'parametro_id',
        // esta armazem os subparamentro do exame e não do resultado
        'subparametro_exame_id',
        'valor',
        'item_exame_id',
        'user_id',
        'entidade_id',
    ];

    public function subparametroexame()
    {
        return $this->belongsTo(SubParamentroExame::class, 'subparametro_exame_id', 'id');
    }

    public function item_exame()
    {
        return $this->belongsTo(ExameItem::class, 'item_exame_id', 'id');
    }

    public function resultado()
    {
        return $this->belongsTo(ResultadoExame::class, 'resultado_id', 'id');
    }

    public function paramentro()
    {
        return $this->belongsTo(ResultadoExameParamentro::class, 'parametro_id', 'id');
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
