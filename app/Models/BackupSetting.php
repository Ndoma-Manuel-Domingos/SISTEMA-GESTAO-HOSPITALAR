<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackupSetting extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'user_id',
        'folder_path',
        'enabled',
        'retain',
        'frequency_minutes',
        'last_run_at',
        'tipo_mysql',
        'entidade_id'
    ];
}
