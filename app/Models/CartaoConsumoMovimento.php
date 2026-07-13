<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class CartaoConsumoMovimento extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = "cartoes_consumos_movimentos";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "cartao_id",
        "saldo",
        "descricao",
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
