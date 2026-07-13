<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'status',
        'codigo_postal',
        'morada',
        'nif',
        'logotipo',
        'ramo_actividade_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'telefone',
        'modelo_factura',
        'email',
        'cae',
        'descricao',
        'observacao',
        'user_id',
        'entidade_id',
    ];

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }

    public function ramo()
    {
        return $this->belongsTo(TipoEntidade::class, 'ramo_actividade_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }

    public function estoques()
    {
        return $this->hasOne(Estoque::class);
    }

    public function produtos_estoques()
    {
        return $this->hasMany(Estoque::class);
    }

    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'id', 'loja_id');
    }

    public function loja_produtos()
    {
        return $this->hasMany(LojaProduto::class);
    }

    public function caixas()
    {
        return $this->hasMany(Caixa::class, 'loja_id', 'id');
    }

    public function bancos()
    {
        return $this->hasMany(ContaBancaria::class);
    }
}
