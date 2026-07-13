<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParamentroExame extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'paramentros_exames';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'exame_id',
        'ordem',
        'observacao',
        'user_id',
        'entidade_id',
    ];

    public function exame()
    {
        return $this->belongsTo(Produto::class, 'exame_id', 'id');
    }

    public function subparamentros()
    {
        return $this->hasMany(SubParamentroExame::class, 'parametro_id', 'id');
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
