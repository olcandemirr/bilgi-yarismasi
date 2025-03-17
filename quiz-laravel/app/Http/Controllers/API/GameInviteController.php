<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GameInvite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameInviteController extends Controller
{
    /**
     * Kullanıcının aktif oyun davetlerini listele
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Kullanıcının aldığı ve gönderdiği davetleri getir
        $invites = GameInvite::where(function($query) use ($user) {
                $query->where('receiver_id', $user->id)
                      ->orWhere('sender_id', $user->id);
            })
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($invites);
    }

    /**
     * Yeni oyun daveti gönder
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'game_config' => 'nullable|array',
        ]);
        
        $user = $request->user();
        $receiverId = $request->receiver_id;
        
        // Kendine davet göndermeyi engelle
        if ($user->id == $receiverId) {
            return response()->json(['message' => 'Kendinize oyun daveti gönderemezsiniz'], 422);
        }
        
        // Var olan aktif daveti kontrol et
        $existingInvite = GameInvite::where(function($query) use ($user, $receiverId) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $receiverId);
            })
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();
        
        if ($existingInvite) {
            return response()->json([
                'message' => 'Bu kullanıcıya zaten aktif bir davet gönderdiniz',
                'invite' => $existingInvite
            ], 422);
        }
        
        // Yeni bir davet oluştur
        $gameInvite = GameInvite::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'game_id' => Str::uuid(),
            'status' => 'pending',
            'categories' => $request->categories ?? null,
            'game_config' => $request->game_config ?? null,
            'expires_at' => Carbon::now()->addHours(1), // 1 saat geçerli
        ]);
        
        // Daveti ilişkiler ile birlikte al
        $gameInvite->load(['sender', 'receiver']);
        
        return response()->json([
            'message' => 'Oyun daveti gönderildi',
            'invite' => $gameInvite
        ], 201);
    }

    /**
     * Oyun davetini kabul et
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept($id, Request $request)
    {
        $user = $request->user();
        
        $invite = GameInvite::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$invite) {
            return response()->json(['message' => 'Geçerli bir oyun daveti bulunamadı'], 404);
        }
        
        $invite->status = 'accepted';
        $invite->save();
        
        return response()->json([
            'message' => 'Oyun daveti kabul edildi',
            'invite' => $invite->load(['sender', 'receiver']),
            'game_url' => url("/game/{$invite->game_id}")
        ]);
    }

    /**
     * Oyun davetini reddet
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reject($id, Request $request)
    {
        $user = $request->user();
        
        $invite = GameInvite::where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if (!$invite) {
            return response()->json(['message' => 'Geçerli bir oyun daveti bulunamadı'], 404);
        }
        
        $invite->status = 'rejected';
        $invite->save();
        
        return response()->json(['message' => 'Oyun daveti reddedildi']);
    }

    /**
     * Belirli bir oyun davetini göster
     *
     * @param  string  $gameId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($gameId, Request $request)
    {
        $user = $request->user();
        
        $invite = GameInvite::where('game_id', $gameId)
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->first();
        
        if (!$invite) {
            return response()->json(['message' => 'Oyun bulunamadı'], 404);
        }
        
        // Kategorileri getir
        $categories = [];
        if ($invite->categories) {
            $categories = Category::whereIn('id', $invite->categories)->get();
        }
        
        return response()->json([
            'invite' => $invite,
            'categories' => $categories,
            'is_participant' => true
        ]);
    }

    /**
     * Süresi dolmuş davetleri otomatik olarak güncelle (cron job için)
     *
     * @return \Illuminate\Http\Response
     */
    public function cleanupExpired()
    {
        $count = GameInvite::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);
        
        return response()->json([
            'message' => "$count adet süresi dolmuş davet temizlendi"
        ]);
    }
}
