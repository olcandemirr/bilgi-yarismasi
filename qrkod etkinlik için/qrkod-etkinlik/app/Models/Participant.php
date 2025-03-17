<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'email',
        'ticket_type',
        'qr_code',
        'google_form_response_id'
    ];

    protected $casts = [
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
