<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;




class ItemDevolucao extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $table = 'items_devolucoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'devolucao_id',
        'produto_id',
        'quantidade',
        'user_id',
        'entidade_id',
    ];
    
    public function produto()
    {
        return $this->belongsTo(produto::class, 'produto_id', 'id');
    }
    
    public function devolucao()
    {
        return $this->belongsTo(Devolucao::class, 'devolucao_id', 'id');
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
