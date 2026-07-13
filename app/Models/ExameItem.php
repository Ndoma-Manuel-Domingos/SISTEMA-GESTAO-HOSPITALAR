<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExameItem extends Model
{

    use HasFactory;

    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'exames_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produto_id',
        'exame_id',
        'valor',
        'status',
        'resultado',
        'observacoes_resultado',
        'data_realizacao',
        'hora_realizacao',
        'user_id',
        'entidade_id',
    ];

    public function resultado_parametro_exame()
    {
        return $this->hasMany(ResultadoExameParamentro::class, 'exame_id', 'produto_id');
    }


    public function paramentos_exames()
    {
        return $this->hasMany(ResultadoExameSubParamentro::class, 'item_exame_id', 'id');
    }

    public function paramentos_exames_imagem()
    {
        return $this->hasMany(ResultadoExameSubParamentroImagem::class, 'item_exame_id', 'id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    public function exame()
    {
        return $this->belongsTo(Exame::class, 'exame_id', 'id');
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
