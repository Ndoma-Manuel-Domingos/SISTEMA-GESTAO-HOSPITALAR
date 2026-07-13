<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'course',
        'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'date',
    ];

    protected function fullName(): Attribute
    {
        return Attribute::get(fn() => trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')));
    }

    public function scopeSearchName($query, $term)
    {
        if (empty($term)) return $query;
        $term = "%{$term}%";
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'like', $term)
                ->orWhere('last_name', 'like', $term)
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", [$term]);
        });
    }
}
