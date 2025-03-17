<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    /**
     * Get all results
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Result::orderBy('score', 'desc')->get();
        return response()->json($results);
    }

    /**
     * Store a newly created result
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string',
            'participant_count' => 'required|integer|min:1',
            'score' => 'required|integer',
            'categories' => 'nullable|array'
        ]);

        $data = $request->only(['team_name', 'participant_count', 'score']);
        
        // Kategorileri JSON olarak kaydet
        if ($request->has('categories')) {
            $data['categories'] = json_encode($request->categories);
        }
        
        // Eğer kullanıcı giriş yapmışsa, user_id ekle
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
            
            // Aynı kullanıcı ve takım adıyla daha önce kaydedilmiş sonuç var mı kontrol et
            $existingResult = Result::where('user_id', Auth::id())
                ->where('team_name', $request->team_name)
                ->first();
            
            if ($existingResult) {
                // Eğer yeni skor daha yüksekse, mevcut skoru güncelle
                if ($request->score > $existingResult->score) {
                    $existingResult->score = $request->score;
                    
                    // Kategorileri güncelle
                    if ($request->has('categories')) {
                        $existingResult->categories = json_encode($request->categories);
                    }
                    
                    $existingResult->save();
                    return response()->json($existingResult, 200);
                } else {
                    // Yeni skor daha düşükse, mevcut sonucu döndür
                    return response()->json($existingResult, 200);
                }
            }
        } else {
            // Kullanıcı giriş yapmamışsa, takım adıyla daha önce kaydedilmiş sonuç var mı kontrol et
            $existingResult = Result::whereNull('user_id')
                ->where('team_name', $request->team_name)
                ->first();
            
            if ($existingResult) {
                // Eğer yeni skor daha yüksekse, mevcut skoru güncelle
                if ($request->score > $existingResult->score) {
                    $existingResult->score = $request->score;
                    
                    // Kategorileri güncelle
                    if ($request->has('categories')) {
                        $existingResult->categories = json_encode($request->categories);
                    }
                    
                    $existingResult->save();
                    return response()->json($existingResult, 200);
                } else {
                    // Yeni skor daha düşükse, mevcut sonucu döndür
                    return response()->json($existingResult, 200);
                }
            }
        }

        // Mevcut sonuç yoksa yeni bir sonuç oluştur
        $result = Result::create($data);
        return response()->json($result, 201);
    }

    /**
     * Display the specified result
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Result::findOrFail($id);
        return response()->json($result);
    }

    /**
     * Get top scores
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function topScores(Request $request)
    {
        $limit = $request->input('limit', 10);
        $withUsers = $request->has('with_users');
        
        $query = Result::orderBy('score', 'desc')
            ->take($limit);
        
        // Kullanıcı bilgilerini de ekle
        if ($withUsers) {
            $query->with('user');
        }
        
        $results = $query->get();
        
        return response()->json($results);
    }
    
    /**
     * Get current user's stats
     *
     * @return \Illuminate\Http\Response
     */
    public function userStats()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $userId = Auth::id();
        
        // Toplam oyun sayısı
        $totalGames = Result::where('user_id', $userId)->count();
        
        // En yüksek skor
        $highScore = Result::where('user_id', $userId)
            ->orderBy('score', 'desc')
            ->value('score') ?? 0;
        
        // Son 5 oyun
        $recentGames = Result::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Kategori bazında istatistikler
        $categoryStats = [];
        $results = Result::where('user_id', $userId)->get();
        
        foreach ($results as $result) {
            if (!empty($result->categories)) {
                $categories = null;
                
                // JSON parse işlemini güvenli hale getir
                if (is_string($result->categories)) {
                    try {
                        $categories = json_decode($result->categories, true);
                    } catch (\Exception $e) {
                        Log::error('JSON parse error: ' . $e->getMessage());
                    }
                } elseif (is_array($result->categories)) {
                    $categories = $result->categories;
                }
                
                if (is_array($categories)) {
                    foreach ($categories as $categoryId) {
                        if (!isset($categoryStats[$categoryId])) {
                            $categoryStats[$categoryId] = [
                                'count' => 0,
                                'total_score' => 0,
                                'high_score' => 0
                            ];
                        }
                        
                        $categoryStats[$categoryId]['count']++;
                        $categoryStats[$categoryId]['total_score'] += $result->score;
                        
                        if ($result->score > $categoryStats[$categoryId]['high_score']) {
                            $categoryStats[$categoryId]['high_score'] = $result->score;
                        }
                    }
                }
            }
        }
        
        // Oyun geçmişi
        $gameHistory = Result::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return response()->json([
            'total_games' => $totalGames,
            'high_score' => $highScore,
            'recent_games' => $recentGames,
            'category_stats' => $categoryStats,
            'game_history' => $gameHistory
        ]);
    }

    /**
     * Get current user's game history
     *
     * @return \Illuminate\Http\Response
     */
    public function userGameHistory()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $userId = Auth::id();
        
        // Kullanıcının tüm oyun geçmişi
        $gameHistory = Result::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($gameHistory);
    }
}
