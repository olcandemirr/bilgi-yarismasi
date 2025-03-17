<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question',
        'options',
        'correct_answer',
        'difficulty',
        'category_id',
        'time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'integer',
        'time' => 'integer',
    ];

    /**
     * Get the category that owns the question
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
