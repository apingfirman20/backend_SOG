<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coments;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ComentsController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'news_id' => 'required|exists:news,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Coments::create($validator->validated());

        return response()->json([
            'message' => 'Comment added successfully!',
            'data' => $comment
        ], 201);
    }

    public function getComments($news_id): JsonResponse
    {
        $comments = Coments::where('news_id', $news_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'news_id' => $news_id,
            'comments' => $comments
        ]);
    }
}
