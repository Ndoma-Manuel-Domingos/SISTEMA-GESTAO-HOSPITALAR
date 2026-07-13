<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Leito extends Model
{
    use HasFactory;
    use SoftDeletes;


    // Especificando o nome da tabela
    protected $table = 'leitos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'quarto_id',
        'user_id',
        'entidade_id',
    ];

    public function internamento()
    {
        return $this->hasOne(Internamento::class, 'leito_id', 'id');
    }

    public function internamentos()
    {
        return $this->hasMany(Internamento::class, 'leito_id', 'id');
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class, 'quarto_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
