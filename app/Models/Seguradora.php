<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seguradora extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'seguradoras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'numero',
        'contacto',

        'tipo',
        'nome',
        'nome_fantasia',
        'sigla',
        'nif',
        'telefone',
        'telefone_secundario',
        'email',
        'website',
        'endereco',
        'cidade',
        'provincia',
        'pais',
        'pessoa_contato',
        'telefone_contato',
        'observacoes',
        'email',

        'user_id',
        'entidade_id',
    ];

    public function planos()
    {
        return $this->hasMany(SeguradoraPlano::class, 'seguradora_id', 'id');
    }

    public function facturas()
    {
        return $this->hasMany(FacturaSeguradora::class, 'seguradora_id', 'id');
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
