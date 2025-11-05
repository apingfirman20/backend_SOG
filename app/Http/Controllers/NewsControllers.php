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
    { $news = News::with('mediaFiles', 'comments')->get();

    return response()->json($news);

    }
  public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'company' => 'required|string',
        'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
    ]);


    $news = News::create([
        'title' => $validated['title'],
        'deskripsi' => $validated['deskripsi'],
        'company' => $validated['company'],
    ]);


    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            $path = $file->store('uploads/news', 'public');

            $type = str_starts_with($file->getMimeType(), 'image') ? 'image' : 'video';

            MediaFile::create([
                'news_id' => $news->id,   // ini penting! 🔥
                'filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'type' => $type,
            ]);
        }
    }

    return response()->json(['message' => 'News created successfully', 'data' => $news]);
}


  public function update(Request $request, $id)
{
    $news = News::findOrFail($id);

    // Validasi input
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'company' => 'required|string|max:255',
        'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
    ]);

    // Update data utama
    $news->update([
        'title' => $validated['title'],
        'deskripsi' => $validated['deskripsi'],
        'company' => $validated['company'],
    ]);

    // Jika ada file baru, tambahkan
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            // Simpan di folder yang sama seperti store()
            $path = $file->store('uploads/news', 'public');

            // Tentukan jenis media
            $type = str_starts_with($file->getMimeType(), 'image') ? 'image' : 'video';

            // Simpan ke DB
            MediaFile::create([
                'news_id' => $news->id,
                'filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'type' => $type,
            ]);
        }
    }

    // Muat ulang relasi media agar frontend langsung dapat data terbaru
    $news->load('mediaFiles');

    // Kembalikan response JSON
    return response()->json([
        'message' => 'Berhasil update data berita',
        'data' => $news,
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

