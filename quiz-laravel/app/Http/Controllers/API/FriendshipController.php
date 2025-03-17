<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendshipController extends Controller
{
    /**
     * Kullanıcının tüm arkadaşlarını listele
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Kabul edilmiş arkadaşlıkları bul (gönderilen ve alınan)
        $friends = DB::table('users')
            ->join('friendships', function ($join) use ($user) {
                $join->on('users.id', '=', 'friendships.friend_id')
                    ->where('friendships.user_id', '=', $user->id)
                    ->where('friendships.status', '=', 'accepted');
            })
            ->orWhere(function ($query) use ($user) {
                $query->join('friendships', function ($join) use ($user) {
                    $join->on('users.id', '=', 'friendships.user_id')
                        ->where('friendships.friend_id', '=', $user->id)
                        ->where('friendships.status', '=', 'accepted');
                });
            })
            ->select('users.id', 'users.name', 'users.username', 'users.avatar', 'users.online_status', 'users.last_online_at', 'users.total_score')
            ->get();
        
        return response()->json($friends);
    }

    /**
     * Bekleyen arkadaşlık isteklerini listele
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pendingRequests(Request $request)
    {
        $user = $request->user();
        
        // Kullanıcıya gelen bekleyen istekler
        $pendingRequests = DB::table('users')
            ->join('friendships', function ($join) use ($user) {
                $join->on('users.id', '=', 'friendships.user_id')
                    ->where('friendships.friend_id', '=', $user->id)
                    ->where('friendships.status', '=', 'pending');
            })
            ->select('users.id', 'users.name', 'users.username', 'users.avatar', 'friendships.id as friendship_id', 'friendships.created_at')
            ->get();
        
        return response()->json($pendingRequests);
    }

    /**
     * Kullanıcıya göre kullanıcı ara
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return response()->json(['message' => 'Arama sorgusu gerekli'], 422);
        }
        
        $users = User::where('id', '!=', $request->user()->id)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('username', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'username', 'avatar', 'online_status')
            ->limit(10)
            ->get();
        
        // Mevcut arkadaşlık durumlarını kontrol et
        $userId = $request->user()->id;
        foreach ($users as $user) {
            $friendship = Friendship::where(function($q) use ($userId, $user) {
                $q->where('user_id', $userId)
                  ->where('friend_id', $user->id);
            })->orWhere(function($q) use ($userId, $user) {
                $q->where('user_id', $user->id)
                  ->where('friend_id', $userId);
            })->first();
            
            if ($friendship) {
                $user->friendship_status = $friendship->status;
                $user->friendship_id = $friendship->id;
            } else {
                $user->friendship_status = null;
            }
        }
        
        return response()->json($users);
    }

    /**
     * Arkadaşlık isteği gönder
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);
        
        $user = $request->user();
        $friendId = $request->friend_id;
        
        // Kendine istek göndermeyi engelle
        if ($user->id == $friendId) {
            return response()->json(['message' => 'Kendinize arkadaşlık isteği gönderemezsiniz'], 422);
        }
        
        // Var olan arkadaşlık ilişkisi kontrolü
        $existingFriendship = Friendship::where(function($q) use ($user, $friendId) {
            $q->where('user_id', $user->id)
              ->where('friend_id', $friendId);
        })->orWhere(function($q) use ($user, $friendId) {
            $q->where('user_id', $friendId)
              ->where('friend_id', $user->id);
        })->first();
        
        if ($existingFriendship) {
            return response()->json([
                'message' => 'Bu kullanıcı ile zaten bir arkadaşlık ilişkiniz var',
                'status' => $existingFriendship->status
            ], 422);
        }
        
        // Yeni arkadaşlık isteği oluştur
        $friendship = Friendship::create([
            'user_id' => $user->id,
            'friend_id' => $friendId,
            'status' => 'pending'
        ]);
        
        return response()->json([
            'message' => 'Arkadaşlık isteği gönderildi',
            'friendship' => $friendship
        ], 201);
    }

    /**
     * Arkadaşlık isteğini kabul et
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept($id, Request $request)
    {
        $user = $request->user();
        
        $friendship = Friendship::where('id', $id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$friendship) {
            return response()->json(['message' => 'Arkadaşlık isteği bulunamadı'], 404);
        }
        
        $friendship->status = 'accepted';
        $friendship->save();
        
        $friend = User::find($friendship->user_id);
        
        return response()->json([
            'message' => 'Arkadaşlık isteği kabul edildi',
            'friend' => [
                'id' => $friend->id,
                'name' => $friend->name,
                'username' => $friend->username,
                'avatar' => $friend->avatar,
                'online_status' => $friend->online_status
            ]
        ]);
    }

    /**
     * Arkadaşlık isteğini reddet
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reject($id, Request $request)
    {
        $user = $request->user();
        
        $friendship = Friendship::where('id', $id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$friendship) {
            return response()->json(['message' => 'Arkadaşlık isteği bulunamadı'], 404);
        }
        
        $friendship->status = 'rejected';
        $friendship->save();
        
        return response()->json(['message' => 'Arkadaşlık isteği reddedildi']);
    }

    /**
     * Arkadaşlık ilişkisini kaldır
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        
        $friendship = Friendship::where(function($q) use ($user, $id) {
            $q->where('user_id', $user->id)
              ->where('friend_id', $id);
        })->orWhere(function($q) use ($user, $id) {
            $q->where('user_id', $id)
              ->where('friend_id', $user->id);
        })->first();
        
        if (!$friendship) {
            return response()->json(['message' => 'Arkadaşlık ilişkisi bulunamadı'], 404);
        }
        
        $friendship->delete();
        
        return response()->json(['message' => 'Arkadaşlık ilişkisi silindi']);
    }
}
