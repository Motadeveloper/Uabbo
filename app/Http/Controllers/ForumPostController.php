<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumPostController extends Controller
{
    /**
     * Exibe todos os posts no fórum.
     */
    public function index()
    {
        $topics = ForumPost::orderBy('created_at', 'desc')->get();
        return view('home', compact('topics'));
    }

    /**
     * Armazena um novo post no fórum.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'thumbnail_url' => 'nullable|string',
            'room_data' => 'nullable|array',
            'description' => 'nullable|string|max:300',
        ]);

        $data['user_id'] = Auth::id();

        ForumPost::create($data);

        return response()->json(['success' => 'Post criado com sucesso!']);
    }
}
