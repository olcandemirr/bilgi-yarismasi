<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'event_date',
        'location',
        'max_participants',
        'qr_code',
        'is_active'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
} 