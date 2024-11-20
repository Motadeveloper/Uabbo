<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HabboController extends Controller
{
    // Método para exibir o formulário de entrada
    public function showForm()
    {
        return view('habbo.form');
    }

    // Método para buscar as informações do Habbo a partir da API
    public function fetchHabboInfo(Request $request)
    {
        // Valida o campo de entrada
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $username = $request->input('username');
        
        // URL fixa para a API do Habbo Brasil
        $url = "https://www.habbo.com.br/api/public/users?name=" . urlencode($username);

        try {
            // Log para monitorar a URL usada na API
            Log::info("Consultando API Habbo com URL: {$url}");

            // Requisição para a API do Habbo Brasil
            $response = Http::withOptions([
                'verify' => false // Desabilita a verificação SSL para evitar problemas de certificado
            ])->get($url);

            // Verifica se a resposta foi bem-sucedida
            if ($response->successful()) {
                $data = $response->json();

                // Verifica se o perfil é privado
                if (isset($data['profileVisible']) && !$data['profileVisible']) {
                    return back()->withErrors([
                        'username' => 'Este usuário possui um perfil privado no Habbo. Para confirmar o registro da conta, é necessário liberar a visibilidade do perfil nas configurações do Habbo Hotel.'
                    ]);
                }

                // Verifica se realmente obteve os dados do usuário
                if (isset($data['uniqueId']) && !empty($data['uniqueId'])) {
                    return view('habbo.info', ['habboData' => $data]);
                } else {
                    Log::warning("Dados não encontrados para o usuário: {$username}");
                    return back()->withErrors(['username' => 'Usuário não encontrado na API do Habbo.']);
                }
            } else {
                // Log detalhado para o erro
                Log::error("Erro ao acessar API Habbo. Status: " . $response->status());
                Log::error("Resposta da API: " . $response->body());

                return back()->withErrors([
                    'username' => 'Erro ao acessar a API do Habbo. Status: ' . $response->status()
                ]);
            }
        } catch (\Exception $e) {
            // Em caso de exceção, exibe uma mensagem de erro e adiciona log
            Log::error("Erro ao acessar API do Habbo: " . $e->getMessage());

            return back()->withErrors([
                'username' => 'Erro ao acessar a API do Habbo. Tente novamente mais tarde. Detalhes: ' . $e->getMessage()
            ]);
        }
    }
}
