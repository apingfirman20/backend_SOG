<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\News;
use App\Models\MediaFile;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;



class NewsControllers extends Controller
{
    public function index()
    {
          $news = News::with('media')->get();
             return response()->json($news);
    }
    public function store(Request $request)
    {
          $validated = $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'company' => 'required|string|max:255',
            'file' => 'file|mimes:jpg,jpeg,png,mp4|max:5120', // max 5MB
        ]);

        // Simpan file ke storage
        $file = $request->file('file');
        $path = $file->store('media', 'public');

        // Simpan data media ke tabel media_files
        $media = MediaFile::create([
            'user_id' => 1, // atau pakai auth()->id() kalau login
            'filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'type' => str_contains($file->getMimeType(), 'image') ? 'image' : 'video',
        ]);

        // Simpan berita dan relasikan ke media
        $news = News::create([
            'title' => $validated['title'],
            'deskripsi' => $validated['deskripsi'],
            'company' => $validated['company'],
            'media_id' => $media->id,
        ]);

        return response()->json([
            'message' => 'Berita dan file berhasil disimpan',
            'data' => $news->load('media'),
        ], 201);
    }

    public function updateNews(Request $request, $id)
    {
         $news = News::find($id);
    if (!$news) {
        return response()->json(['message' => 'News not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'sometimes|string|max:255',
        'deskripsi' => 'sometimes|string',
        'company' => 'sometimes|string|max:255',
        'media.*' => 'file|max:10240',
        'user_id' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Update data artikel
    $news->update($request->only(['title', 'description', 'company']));

    $updatedFiles = [];

    // Jika ada file baru
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $date = Carbon::now()->format('Ymd_His');

            $newFileName = "{$request->user_id}_{$originalName}_{$date}.{$extension}";
            $path = $file->storeAs("news_media/{$news->id}", $newFileName, 'public');

            $type = in_array(strtolower($extension), ['mp4', 'mov', 'avi']) ? 'video' : 'image';

            // Simpan ke DB
            $media = MediaFile::create([
                'user_id' => $request->user_id,
                'filename' => $newFileName,
                'file_path' => $path,
                'type' => $type,
            ]);

            $updatedFiles[] = $media;
        }
    }

    return response()->json([
        'message' => 'News updated successfully',
        'data' => $news,
        'new_files' => $updatedFiles
    ]);
    }

    public function deleteNews($id)
    {
       $news = News::find($id);
    if (!$news) {
        return response()->json(['message' => 'News not found'], 404);
    }

    // Hapus semua media di folder
    $folderPath = "news_media/{$news->id}";
    Storage::disk('public')->deleteDirectory($folderPath);

    // Hapus data file di DB
   MediaFile::where('file_path', 'like', "news_media/{$news->id}/%")->delete();

    // Hapus artikel
    $news->delete();

    return response()->json(['message' => 'News and media deleted successfully']);
    }
    
}

