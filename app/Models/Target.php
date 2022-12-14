<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'cost',
        'gain',
        'priority',
        'deadline'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }
}
