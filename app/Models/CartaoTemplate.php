<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class CartaoTemplate extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'cartao_templates';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'width',
        'height',
        'height_logo',
        'orientation',
        'rotacao_fundo',
        'border_radius',
        'border_top_space',
        'border_top_color',
        'border_bottom_space',
        'border_bottom_color',
        'font_family',
        'font_size',
        'font_size_title',
        'font_size_subtitle',
        'text_color',
        'line_height',
        'background_image',
        'background_color',
        'background_color_segunda',
        'background_color_terceira',
        'photo_position',
        'logo_position',
        'border_logo',
        'border_logo_color',
        'border_logo_radius',
        'positions',
        'opacity',
        'filter',
        'user_id',
        'entidade_id',
    ];
    
    protected $casts = [
        'positions' => 'array'
    ];
    
}
