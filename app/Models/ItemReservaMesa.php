<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class ItemReservaMesa extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'items_reservas_mesas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reserva_id',
        'mesa_id',
        'user_id',
        'entidade_id',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id', 'id');
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'id');
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
