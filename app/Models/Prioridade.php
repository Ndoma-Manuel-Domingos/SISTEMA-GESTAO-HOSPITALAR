<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Prioridade extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'prioridades';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'cor',
        'user_id',
        'entidade_id',
    ];

    public function tipo_cor($string)
    {
        if($string == "#FF4C4C")  return "🔴";
        if($string == "#FF9900")  return "🟠";
        if($string == "#FFCC00")  return "🟡";
        if($string == "#66CC66")  return "🟢";
        if($string == "#4DA6FF")  return "🔵";
        if($string == "#CCCCCC")  return "⚪";
        if($string == "#800080")  return "🟣";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     
}
