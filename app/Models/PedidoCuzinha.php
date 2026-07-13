<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;




class PedidoCuzinha extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $table = 'pedidos_cuzinhas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'status',
        'factura_id',
        'mesa_id',
        'status2',
        'user_id',
        'entidade_id',
    ];
    
    // Acessório para tempo de espera
    public function getTempoDeEsperaAttribute()
    {
        return now()->diffForHumans($this->created_at);
    }
    
    public function getCreatedAtTimestampAttribute()
    {
        return $this->created_at->timestamp;
    }
    
    public function items()
    {
        return $this->hasMany(ItemPedidoCuzinha::class, 'pedido_id', 'id');
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
