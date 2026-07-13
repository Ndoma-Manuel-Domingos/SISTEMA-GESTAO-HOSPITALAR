<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Serie extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'series';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'seriesCode',
        'seriesYear',
        'documentType',
        'firstDocumentNo',
        'lastDocumentNo',
        'firstDocumentCreated',
        'lastDocumentCreated',
        'submissionUUID',
        'authorizedQuantity',
        'taxRegistrationNumber',
        'seriesContingencyIndicator',
        'establishmentNumber',
        'loja_id',
        'entidade_id',
        'user_id',
    ];
  
    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id', 'id');
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
