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
        'score'
    ];

    protected $casts = [
        'participant_count' => 'integer',
        'score' => 'integer'
    ];
}
