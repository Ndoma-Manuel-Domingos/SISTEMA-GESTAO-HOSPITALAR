<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;




class License extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'licenses';

    protected $fillable = [
        'file_name','signature','payload','issued_by','activated_for_company_id',
        'activated_on_device_id','start_date','end_date','issued_at','activated_at',
        'used','status','path','___status'
    ];
    
    protected $casts = [
        'payload' => 'array',
        'start_date' => 'encrypted',
        'end_date' => 'encrypted',
        'issued_at' => 'datetime',
        'activated_at' => 'datetime',
        'used' => 'boolean'
    ];

}
