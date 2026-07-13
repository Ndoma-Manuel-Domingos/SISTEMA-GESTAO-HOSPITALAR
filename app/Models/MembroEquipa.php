<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembroEquipa extends Model
{

    use HasFactory;

    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'membros_equipas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profissional_id',
        'equipa_id',
        'cargo',
        'user_id',
        'entidade_id',
    ];

    public function equipa()
    {
        return $this->belongsTo(Equipa::class, 'equipa_id', 'id');
    }

    public function profissional()
    {
        return $this->belongsTo(Funcionario::class, 'profissional_id', 'id');
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
