<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoMedicaItem extends Model
{
    use HasFactory;

    use SoftDeletes;
    // Especificando o nome da tabela
    protected $table = 'solicitacoes_medica_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produto_id',
        'solicitacao_medica_id',
        'status',
        'user_id',
        'entidade_id',
    ];

    public function solicitacao()
    {
        return $this->belongsTo(SolicitacaoMedica::class, 'solicitacao_medica_id', 'id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
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
