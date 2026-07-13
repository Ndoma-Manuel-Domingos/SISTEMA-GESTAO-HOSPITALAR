<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reserva extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'reservas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
        'codigo_referencia',
        'valor_total',
        'valor_pago',
        'valor_divida',
        'valor_troco',
        'valor_retencao_fonte',

        'motivo_reserva_id',
        'tipo_reserva_id',
        'criancas',
        'numero_criancas',

        // 'tarefario_id',x
        'total_pessoas',
        'subconta_id',
        'forma_pagamento_id',


        'data_registro',
        'data_inicio',
        'data_final',
        'hora_entrada',
        'hora_saida',
        'status',
        'total_dias',
        'cliente_id',
        'pagamento',
        // 'quarto_id',

        'data_check_in',
        'hora_check_in',
        'data_check_out',
        'hora_check_out',
        'user_check_in',
        'user_check_out',
        'check',
        'code',
        'observacao',

        'loja_id',
        'exercicio_id',
        'periodo_id',
        'data_entrada',
        'data_saida',
        'user_id',
        'entidade_id',
    ];

    public static function gerarCodigoReferencia()
    {
        do {
            $codigo = strtoupper(Str::random(5)); // Gera 5 caracteres aleatórios
        } while (self::where('codigo_referencia', $codigo)->exists()); // Garante unicidade
    
        return $codigo;
    }

    public function items()
    {
        return $this->hasMany(ItemReserva::class, 'reserva_id', 'id');
    }

    public function motivo()
    {
        return $this->belongsTo(MotivoReserva::class, 'motivo_reserva_id', 'id');
    }

    public function tipo_reserva()
    {
        return $this->belongsTo(TipoReserva::class, 'tipo_reserva_id', 'id');
    }

    public function subconta()
    {
        return $this->belongsTo(Subconta::class, 'subconta_id', 'id');
    }

    // public function tarefario()
    // {
    //     return $this->belongsTo(Produto::class, 'tarefario_id', 'id');
    // }

    public function user_in_ckeck()
    {
        return $this->belongsTo(User::class, 'user_check_in', 'id');
    }

    public function user_out_ckeck()
    {
        return $this->belongsTo(User::class, 'user_check_out', 'id');
    }

    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id', 'id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class, 'quarto_id', 'id');
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
