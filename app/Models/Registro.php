<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registro extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'registro',
        'documento',
        'documento_id',
        'data_registro',
        'quantidade',
        'preco_unitario',
        'tipo',
        'observacao',
        'encomenda_id',
        'requisicao_id',
        'produto_id',
        'loja_id',
        'lote_id',
        'user_id',
        'user_operar_id',
        'status', // V = venda, D = Devolucao, A = actualizações, E = Expiracao, R = Retorno, T = Transferencia
        'entidade_id',
    ];

    public function sigla($sigla, $descricao)
    {
        if ($sigla == "E") {
        }
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_operar()
    {
        return $this->belongsTo(User::class, 'user_operar_id', 'id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
