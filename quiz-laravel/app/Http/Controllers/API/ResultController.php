<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;

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
            'score' => 'required|integer'
        ]);

        $result = Result::create($request->all());
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
     * @param  int  $limit
     * @return \Illuminate\Http\Response
     */
    public function topScores($limit = 10)
    {
        $results = Result::orderBy('score', 'desc')
            ->take($limit)
            ->get();
        
        return response()->json($results);
    }
}
