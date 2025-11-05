<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coments;
use App\Models\News;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ComentsController extends Controller
{
    /**
     * Simpan komentar baru
     */
    public function store(Request $request, $news_id = null): JsonResponse
    {
        // Jika news_id tidak dikirim lewat body, ambil dari URL
        $newsId = $news_id ?? $request->input('news_id');

        $validator = Validator::make(array_merge($request->all(), ['news_id' => $newsId]), [
            'news_id' => 'required|exists:news,id',
            'name' => 'required|string|max:100',
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $comment = Coments::create($validator->validated());

        return response()->json($comment, 201);
    }

    /**
     * Ambil semua komentar untuk news tertentu
     */
    public function getComments($news_id): JsonResponse
    {
        $comments = Coments::where('news_id', $news_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }
    public function destroy($id): JsonResponse
{
    $comment = Coments::find($id);

    if (!$comment) {
        return response()->json(['message' => 'Comment not found'], 404);
    }

    $comment->delete();

    return response()->json(['message' => 'Comment deleted successfully']);
}

    
}

