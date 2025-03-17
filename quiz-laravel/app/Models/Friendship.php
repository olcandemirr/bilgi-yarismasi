<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'friend_id',
        'status'
    ];
    
    /**
     * Arkadaşlık isteğini gönderen kullanıcı
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Arkadaşlık isteğini alan kullanıcı
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
