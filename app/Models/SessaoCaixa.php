<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class SessaoCaixa extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'sessoes_caixa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'caixa_id',
        'status',
        'data_abertura',
        'hora_abertura',
        'user_fecho',
        'hora_fecho',
        'data_fecho',
        'entidade_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

}


