<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Caixa extends Model
{

    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'status_admin',
        'conta',
        'code',
        'code_caixa',
        'active',
        'continuar_apos_login',
        'user_id',
        'user_open_id',
        'subconta_id',
        'user_close_id',
        'loja_id',
        'entidade_id',
    ];

    public function user_open()
    {
        return $this->belongsTo(User::class, 'user_open_id', 'id');
    }

    public function user_close()
    {
        return $this->belongsTo(User::class, 'user_close_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
