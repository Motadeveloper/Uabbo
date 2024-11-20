<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * Exibe a página inicial com os tópicos paginados.
     */
    public function index()
{
    // Carrega tópicos com a contagem de comentários e respostas
    $topics = Topic::withCount(['comments as total_comments' => function ($query) {
        $query->with('replies'); // Inclui as respostas para a contagem
    }])->with('comments.user')->get();

    return view('feed', compact('topics'));
}



public function apiIndex(Request $request)
{
    $page = $request->get('page', 1);
    $perPage = $request->get('perPage', 7);

    $topics = Topic::with(['user'])
        ->withCount(['comments as total_comments_with_replies' => function ($query) {
            $query->whereNull('parent_id');
        }])
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

    $isAuthenticated = auth()->check();

    // Adicionar lógica no servidor para exibir ou ocultar botões
    $topics->getCollection()->transform(function ($topic) use ($isAuthenticated) {
        $topic->reply_button = $isAuthenticated
            ? "<button class='btn btn-sm btn-primary mt-2' onclick='toggleReplyBox({$topic->id})'>Responder</button>"
            : ''; // Retorna um botão vazio para usuários não logados

        return $topic;
    });

    return response()->json($topics);
}




    
    
    
    /**
     * Exibe um tópico específico e incrementa as visualizações.
     */
    public function show($id)
    {
        $topic = Topic::with(['user', 'comments.user', 'comments.replies.user'])->findOrFail($id);

        // Incrementa o contador de visualizações
        $topic->increment('views');

        return view('topics.show', compact('topic'));
    }

    /**
     * Cria um novo tópico.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:2000', // Validação com limite de caracteres
        ]);

        $topic = Topic::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', 'Tópico criado com sucesso!');
    }

    /**
     * Deleta um tópico.
     */
    public function destroy($id)
{
    $topic = Topic::findOrFail($id);

    if (auth()->id() !== $topic->user_id) {
        return response()->json(['error' => 'Você não tem permissão para apagar este tópico.'], 403);
    }

    $topic->delete();

    return response()->json(['success' => 'Tópico apagado com sucesso.']);
}
}
