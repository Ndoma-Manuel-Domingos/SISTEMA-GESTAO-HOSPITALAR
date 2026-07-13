<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class CartaoConsumo extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = "cartoes_consumos";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nome",
        "status",
        "saldo",
        "user_id",
        "entidade_id",
    ];
    
    public function historicos()
    {
        return $this->hasMany(CartaoConsumoHistorico::class, "cartao_id", "id")->orderBy('created_at', 'desc');;
    }
    
    public function movimentos()
    {
        return $this->hasMany(CartaoConsumoMovimento::class, "cartao_id", "id")->orderBy('created_at', 'desc');;
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
