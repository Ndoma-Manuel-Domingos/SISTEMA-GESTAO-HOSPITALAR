<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class CartaoConsumoHistorico extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = "cartoes_consumos_historicos";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "cartao_id",
        "tipo",
        "saldo",
        "date_at",
        "user_id",
        "entidade_id",
    ];

    public function cartao()
    {
        return $this->belongsTo(CartaoConsumo::class, "cartao_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emtidade()
    {
        return $this->belongsTo(Entidade::class);
    }
     
}
