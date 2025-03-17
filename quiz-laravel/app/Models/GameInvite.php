<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameInvite extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'game_id',
        'status',
        'categories',
        'game_config',
        'expires_at'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'categories' => 'array',
        'game_config' => 'array',
        'expires_at' => 'datetime',
    ];
    
    /**
     * Daveti gönderen kullanıcı
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    
    /**
     * Daveti alan kullanıcı
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    
    /**
     * Davetle ilgili kategoriler
     */
    public function categories()
    {
        return Category::whereIn('id', $this->categories ?? [])->get();
    }
}
