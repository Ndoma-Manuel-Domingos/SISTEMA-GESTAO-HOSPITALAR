<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class ArquivoPasta extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    // Especificando o nome da tabela
    protected $table = 'arquivos_pastas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'code',
        'size_bytes',
        'size_formatted',
        'extension',
        'user_id',
        'parent_id',
        'departamento_id',
        'entidade_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(DepartamentoPasta::class, 'parent_id', 'id');
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
