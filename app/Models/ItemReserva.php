<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemReserva extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'items_reservas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reserva_id',
        'tarefario_id',
        'valor_unitario',
        'total_dias',
        'total_pessoas',
        'cliente_id',
        'quarto_id',
        'valor',
        'numero_criancas',
        'code',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id', 'id');
    }

    public function tarefario()
    {
        return $this->belongsTo(Produto::class, 'tarefario_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class, 'quarto_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
