<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class DepartamentoPasta extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    // Especificando o nome da tabela
    protected $table = 'departamentos_pastas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'code',
        'type',
        'user_id',
        'entidade_id',
    ];

    public function pastas()
    {
        return $this->hasMany(Pasta::class, 'departamento_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(ArquivoPasta::class, 'departamento_id', 'id');
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
