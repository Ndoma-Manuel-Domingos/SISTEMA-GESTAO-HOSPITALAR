<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class UserLoja extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'users_lojas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario_id',
        'loja_id',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, "usuario_id", "id");
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, "loja_id", "id");
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
