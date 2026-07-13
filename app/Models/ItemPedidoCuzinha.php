<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;




class ItemPedidoCuzinha extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $table = 'items_pedidos_cuzinhas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
        'data_em_preparo',
        'data_pronto',
        'data_entregue',
        'data_a_preparar',
        'user_id',
        'entidade_id',
    ];
    
    public function produto()
    {
        return $this->belongsTo(produto::class, 'produto_id', 'id');
    }
    
    public function pedido()
    {
        return $this->belongsTo(PedidoCuzinha::class, 'pedido_id', 'id');
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
