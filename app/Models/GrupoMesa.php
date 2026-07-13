<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class GrupoMesa extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'grupos_mesas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'grupo_id',
        'mesa_id',
        'user_id',
        'entidade_id',
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, "mesa_id", "id");
    }

    public function grupo()
    {
        return $this->belongsTo(User::class, "grupo_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }
}
