<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'location',
        'description',
        'schedule'
    ];

    protected $casts = [
        'date' => 'date',
        'schedule' => 'array'
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
