<?php

namespace App\Http\Controllers;

use App\Models\Sorteio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SorteioController extends Controller
{
    public function index()
    {
        $sorteios = Sorteio::latest()->paginate(10);
        return view('sorteios.index', compact('sorteios'));
    }

    public function show($id)
{
    $sorteio = Sorteio::with('participantes', 'premios')->findOrFail($id);

    // Carregue os vencedores, se o sorteio já foi realizado
    $vencedores = $sorteio->premio_detalhes ?? [];

    // Verificar se o usuário está participando do sorteio
    $participando = Auth::check() ? $sorteio->participantes->contains(Auth::id()) : false;

    return view('sorteios.show', compact('sorteio', 'vencedores', 'participando'));
}

    public function create()
    {
        return view('sorteios.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'premios' => 'required|array',
        'premios.*.premio_tipo' => 'required|string',
        'premios.*.premio_quantidade' => 'required|integer|min:1',
    ]);

    $validated['user_id'] = Auth::id();
    $sorteio = Sorteio::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'user_id' => Auth::id(),
        'quantidade_ganhadores' => count($validated['premios']),
    ]);

    foreach ($validated['premios'] as $index => $premio) {
        $sorteio->premios()->create([
            'posicao' => $index + 1,
            'premio_tipo' => $premio['premio_tipo'],
            'premio_quantidade' => $premio['premio_quantidade'],
        ]);
    }

    return redirect()->route('sorteios.index')->with('success', 'Sorteio criado com sucesso!');
}

    public function participar($id)
    {
        $sorteio = Sorteio::findOrFail($id);
        $sorteio->participantes()->attach(Auth::id());

        return back()->with('success', 'Você entrou no sorteio!');
    }

    public function gerar($id)
{
    $sorteio = Sorteio::with(['participantes', 'premios'])->findOrFail($id);

    // Verifica se o sorteio já foi realizado
    if ($sorteio->data_sorteio) {
        return back()->with('message', 'Este sorteio já possui ganhadores.');
    }

    // Selecione os ganhadores de acordo com a quantidade de prêmios
    $ganhadores = $sorteio->participantes()->inRandomOrder()->take($sorteio->premios->count())->get();
    $premios = $sorteio->premios->sortBy('posicao');

    $premio_detalhes = [];
    foreach ($ganhadores as $index => $ganhador) {
        if (isset($premios[$index])) {
            $premio = $premios[$index];
            $premio_detalhes[] = [
                'user_name' => $ganhador->name,
                'user_id' => $ganhador->id,
                'posicao' => $premio->posicao,
                'premio_tipo' => $premio->premio_tipo,
                'premio_quantidade' => $premio->premio_quantidade,
            ];
        }
    }

    // Atualize os detalhes do sorteio com os vencedores
    $sorteio->update([
        'data_sorteio' => now(),
        'premio_detalhes' => $premio_detalhes,
    ]);

    return redirect()->route('sorteios.show', $sorteio->id)->with('message', 'Sorteio realizado com sucesso!');
}

    public function cancelar($id)
    {
        $sorteio = Sorteio::findOrFail($id);
        $sorteio->delete();

        return redirect()->route('sorteios.index')->with('success', 'Sorteio cancelado.');
    }

    public function gerarVencedor($id)
    {
        $sorteio = Sorteio::findOrFail($id);

        // Verificar se o sorteio já tem um ganhador
        if ($sorteio->vencedor_id) {
            return redirect()->route('sorteios.show', $id)
                            ->with('message', 'Este sorteio já possui um vencedor.');
        }

        // Selecionar um vencedor aleatório
        $vencedor = $sorteio->participantes()->inRandomOrder()->first();

        // Salvar o vencedor e a data/hora do sorteio
        $sorteio->update([
            'vencedor_id' => $vencedor->id,
            'data_sorteio' => now(),
        ]);

        return redirect()->route('sorteios.show', $id)
                        ->with('message', 'Vencedor gerado com sucesso!');
    }

    public function createForm()
{
    return view('sorteios.create');
}

public function getSorteios(Request $request)
{
    $perPage = $request->get('perPage', 5);
    $page = $request->get('page', 1);
    $offset = ($page - 1) * $perPage;

    // Carregar sorteios em andamento com participantes e prêmios
    $sorteiosEmAndamento = Sorteio::whereNull('data_sorteio')
        ->with(['author', 'premios', 'participantes'])
        ->offset($offset)
        ->limit($perPage)
        ->get()
        ->map(function($sorteio) {
            return [
                'id' => $sorteio->id,
                'title' => $sorteio->title,
                'description' => $sorteio->description,
                'author_name' => $sorteio->author->name ?? 'Anônimo',
                'data_sorteio_formatado' => optional($sorteio->data_sorteio)->format('d/m/Y H:i') ?? null,
                'premios' => $sorteio->premios, // ou mapeie para a estrutura desejada
                'participantes' => $sorteio->participantes->map(function ($participante) {
                    return [
                        'id' => $participante->id,
                        'name' => $participante->name,
                    ];
                }),
                'total_participantes' => $sorteio->participantes->count(),
                'created_at' => $sorteio->created_at,
            ];
        });

    // Carregar sorteios realizados com prêmios e detalhes de vencedores
    $sorteiosRealizados = Sorteio::whereNotNull('data_sorteio')
        ->with(['author', 'premios', 'participantes'])
        ->offset($offset)
        ->limit($perPage)
        ->get()
        ->map(function($sorteio) {
            return [
                'id' => $sorteio->id,
                'title' => $sorteio->title,
                'description' => $sorteio->description,
                'author_name' => $sorteio->author->name ?? 'Anônimo',
                'data_sorteio_formatado' => optional($sorteio->data_sorteio)->format('d/m/Y H:i') ?? null,
                'premios' => $sorteio->premios,
                'premio_detalhes' => $sorteio->premio_detalhes ?? [],
                'created_at' => $sorteio->created_at,
            ];
        });

    return response()->json([
        'sorteiosEmAndamento' => $sorteiosEmAndamento,
        'sorteiosRealizados' => $sorteiosRealizados,
    ]);
}

}
