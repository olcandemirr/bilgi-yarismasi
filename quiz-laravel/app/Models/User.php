<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'bio',
        'total_score',
        'games_played',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Kullanıcının gönderdiği arkadaşlık istekleri
     */
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    /**
     * Kullanıcının aldığı arkadaşlık istekleri
     */
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    /**
     * Kullanıcının kabul edilmiş arkadaşları
     */
    public function friends()
    {
        return $this->sentFriendRequests()
                    ->where('status', 'accepted')
                    ->with('friend');
    }

    /**
     * Kullanıcının gönderdiği oyun davetleri
     */
    public function sentGameInvites()
    {
        return $this->hasMany(GameInvite::class, 'sender_id');
    }

    /**
     * Kullanıcının aldığı oyun davetleri
     */
    public function receivedGameInvites()
    {
        return $this->hasMany(GameInvite::class, 'receiver_id');
    }

    /**
     * Kullanıcının oyun sonuçları
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
