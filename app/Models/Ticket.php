<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



use DateTime;

class Ticket extends Model
{

    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['number','status','called_at', 'service_id', 'prefix', 'entidade_id', 'user_id'];

    protected $casts = [
        'called_at' => 'datetime',
    ];
    
    public function displayNumber()
    {
        $prefix = $this->service->codigo_barra ?? '';
        return $prefix . str_pad($this->number, 3, '0', STR_PAD_LEFT);
    }
    
    public function service()
    {
        return $this->belongsTo(Produto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

}
