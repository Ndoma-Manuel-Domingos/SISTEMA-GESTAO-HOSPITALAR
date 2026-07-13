<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;




class ConfiguracaoRecursoHumano extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'configuracao_recursos_humanos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'horas_diarias',
        'horas_semanais',
        'caixa_pagamento_id',
        'banco_pagamento_id',
        'dispesa_pagamento_id',
        'entidade_id',
        'user_id',
    ];

}
