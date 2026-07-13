<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaHospitalarMovimento extends Model
{
    use SoftDeletes;

    protected $table = 'contas_hospitalares_movimentos';

    protected $fillable = [
        'conta_hospitalar_id',
        'tipo',
        'descricao',
        'user_id',
        'entidade_id'
    ];

    public function conta()
    {
        return $this->belongsTo(ContaHospitalar::class, 'conta_hospitalar_id', 'id');
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
