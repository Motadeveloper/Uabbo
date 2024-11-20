<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Topic;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Retornar todas as respostas de um tópico.
     */
    public function index(Topic $topic)
    {
        // Retorna comentários principais com suas respostas recursivamente e o autor
        $comments = $topic->comments()
            ->whereNull('parent_id') // Apenas comentários principais
            ->with('user', 'replies.user') // Inclui o autor e respostas encadeadas
            ->get();

        return response()->json($comments, 200);
    }

    /**
     * Criar um comentário para um tópico.
     */
    public function storeReply(Request $request, $topicId)
{
    // Verificar se o usuário está autenticado
    if (!auth()->check()) {
        return response()->json(['error' => 'Usuário não autenticado.'], 401);
    }

    // Validar os dados da requisição
    $validated = $request->validate([
        'content' => 'required|string|max:800',
    ]);

    try {
        // Buscar o tópico pelo ID
        $topic = Topic::findOrFail($topicId);

        // Criar o comentário associado ao tópico
        $comment = $topic->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        // Atualizar a data de atualização do tópico
        $topic->touch();

        // Retornar a resposta JSON com os dados do comentário
        return response()->json([
            'id' => $comment->id,
            'content' => $comment->content,
            'created_at' => $comment->created_at->toDateTimeString(),
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
            ],
        ], 201);

    } catch (\Exception $e) {
        // Retornar erro genérico em caso de falha
        return response()->json([
            'error' => 'Erro ao criar a resposta.',
            'message' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Criar uma resposta para outro comentário.
     */
    public function storeNestedReply(Request $request, Comment $reply)
{   
    $validated = $request->validate([
        'content' => 'required|string|max:800',
    ]);

    $nestedReply = $reply->replies()->create([
        'content' => $validated['content'],
        'user_id' => auth()->id(),
    ]);

    // Atualizar a data de atualização do tópico relacionado ao comentário
    $reply->topic->touch();

    return response()->json([
        'id' => $nestedReply->id,
        'content' => $nestedReply->content,
        'created_at' => $nestedReply->created_at,
        'user' => [
            'id' => $nestedReply->user->id,
            'name' => $nestedReply->user->name,
        ],
    ], 201);
}


public function getComments(Request $request, $topicId)
{
    $limit = $request->get('limit');
    $topic = Topic::findOrFail($topicId);
    $isAuthenticated = auth()->check();

    $comments = $topic->comments()
        ->whereNull('parent_id')
        ->with(['user', 'replies.user'])
        ->take($limit)
        ->get()
        ->map(function ($comment) use ($isAuthenticated) {
            $comment->reply_button = $isAuthenticated
                ? "<button class='btn btn-link btn-sm' onclick='toggleReplyBox({$comment->id})'>Responder</button>"
                : ''; // Retorna vazio para usuários não logados

            return $comment;
        });

    return response()->json($comments);
}




}
