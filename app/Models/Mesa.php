<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;



class Mesa extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'ocupacao',
        'solicitar_ocupacao',
        'sala_id',
        'entidade_id',
    ];

    public function pedidos()
    {
        return $this->hasMany(ItemVenda::class, 'mesa_id')
            ->with('produto')
            ->where('status', 'processo')
            ->where('entidade_id', Auth::user()->entidade_id);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, "mesa_id", "id");
    }
}
