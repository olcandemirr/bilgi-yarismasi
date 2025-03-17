<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
        'participant_count',
        'score',
        'user_id',
        'categories'
    ];

    protected $casts = [
        'participant_count' => 'integer',
        'score' => 'integer',
        'user_id' => 'integer',
        'categories' => 'json'
    ];
    
    /**
     * Get the user that owns the result
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
