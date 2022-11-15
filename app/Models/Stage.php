<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'tolerance',
        'target_id',
        'status',
        'stageid',
        'deadline',
        'complexity'
    ];

    public function target()
    {
        return $this->belongsTo(Target::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class, 'stageid', 'id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stageid', 'id');
    }

    public function isFather()
    {
        return is_null($this->attributes['stageid']);
    }

    protected $appends = ['is_father'];
    public function getIsFatherAttribute()
    {
        return is_null($this->attributes['stageid']);
    }
}
