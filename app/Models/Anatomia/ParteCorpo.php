<?php

namespace App\Models\Anatomia;

use App\Models\Especialidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParteCorpo extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'partes_corpo';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'sistema',
        'especialidade_id'
    ];

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class);
    }
}
