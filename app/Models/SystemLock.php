<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLock extends Model
{
    use HasFactory;
    protected $fillable = ['locked', 'attempts', 'last_attempt_at', 'recovery_token'];
}
