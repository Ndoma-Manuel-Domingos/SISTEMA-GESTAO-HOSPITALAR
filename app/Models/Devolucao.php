<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;




class Devolucao extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $table = 'devolucoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'data_at',
        'motivo',
        'factura_id',
        'user_id',
        'entidade_id',
    ];

    public function items()
    {
        return $this->hasMany(ItemDevolucao::class, 'produto_id', 'id');
    }

    public function factura()
    {
        return $this->belongsTo(Venda::class, 'factura_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'entidade_id', 'id');
    }
}
