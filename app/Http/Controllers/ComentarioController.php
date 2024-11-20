<?php

namespace App\Http\Controllers;

use App\Models\Sorteio;
use App\Models\Comentario; // Supondo que você tenha um modelo Comentario
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request, Sorteio $sorteio)
    {
        $request->validate([
            'comentario' => 'required|string|max:500',
        ]);

        $sorteio->comentarios()->create([
            'conteudo' => $request->comentario,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('sorteios.show', $sorteio->id)->with('success', 'Comentário adicionado com sucesso!');
    }
}