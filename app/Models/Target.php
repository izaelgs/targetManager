<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'categoryid',
        'title',
        'description',
        'cost',
        'gain',
        'priority',
        'deadline',
        'sugested_priority'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
