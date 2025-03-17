<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'checked_in',
        'checked_in_at'
    ];

    protected $casts = [
        'checked_in' => 'boolean',
        'checked_in_at' => 'datetime'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
} 