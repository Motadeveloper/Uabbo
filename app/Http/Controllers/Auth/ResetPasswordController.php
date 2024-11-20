<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class ResetPasswordController extends Controller
{
    // Mostra o formulário de redefinição de senha com um código gerado dinamicamente
    public function showResetForm()
    {
        $habbo_code = bin2hex(random_bytes(5)); // Gera o código único para verificação na missão do Habbo
        session(['habbo_code' => $habbo_code]); // Armazena o código na sessão
        return view('auth.passwords.reset', compact('habbo_code'));
    }

    // Método para redefinir a senha com validação do Habbo Code
    public function sendResetLinkEmail(Request $request)
    {
        // Valida os campos básicos do formulário
        $this->validator($request->all())->validate();

        $data = $request->all();
        $username = $data['name'];
        $habboCode = $data['habbo_code'];

        // Recupera o habbo_code da sessão
        $storedHabboCode = session('habbo_code');

        // Verifica se o código gerado corresponde ao informado pelo usuário
        if (!$storedHabboCode || $habboCode !== $storedHabboCode) {
            return redirect()->back()->withErrors([
                'habbo_code' => 'O código da missão é inválido ou expirou. Tente novamente.',
            ]);
        }

        // Verifica se o código está na missão do Habbo
        $validationResult = $this->validateHabboCode($username, $habboCode);

        if ($validationResult === 'success') {
            // Procura o usuário pelo nome
            $user = User::where('name', $username)->first();

            if (!$user) {
                return redirect()->back()->withErrors([
                    'name' => 'Usuário não encontrado no sistema. Verifique o nome digitado.',
                ]);
            }

            // Atualiza a senha do usuário
            $user->password = Hash::make($data['password']);
            $user->save();

            return redirect('/login')->with('success', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
        } elseif ($validationResult === 'profile_private') {
            return redirect()->back()->withErrors([
                'habbo_code' => 'Para confirmar a redefinição, é necessário liberar a visibilidade do perfil nas configurações do Habbo Hotel.',
            ]);
        } else {
            return redirect()->back()->withErrors([
                'habbo_code' => 'A missão não está igual ao código informado ou seu perfil do Habbo é privado. Verifique e tente novamente.',
            ]);
        }
    }

    // Validação dos campos do formulário com mensagens personalizadas em português
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|exists:users,name',
            'password' => 'required|string|min:8|confirmed',
            'habbo_code' => 'required|string',
        ], [
            'name.required' => 'O campo Nome de Usuário é obrigatório.',
            'name.exists' => 'O usuário informado não foi encontrado.',
            'password.required' => 'O campo Senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'habbo_code.required' => 'É necessário fornecer o código da missão para confirmação.',
        ]);
    }

    // Método para validar o código na missão do Habbo
    protected function validateHabboCode($username, $code)
    {
        $url = "https://www.habbo.com.br/api/public/users?name=" . urlencode($username);

        try {
            $response = Http::withOptions(['verify' => false])->get($url);

            // Debug para logs
            \Log::info('Resposta da API Habbo:', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Verifica se o perfil está visível
                if (isset($data['profileVisible']) && !$data['profileVisible']) {
                    return 'profile_private'; // Perfil privado
                }

                $motto = $data['motto'] ?? '';

                // Normaliza as strings para comparação
                $normalizedMotto = strtolower(trim($motto));
                $normalizedCode = strtolower(trim($code));

                return strpos($normalizedMotto, $normalizedCode) !== false ? 'success' : 'motto_mismatch';
            } else {
                return 'motto_mismatch';
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao chamar a API Habbo:', ['exception' => $e->getMessage()]);
            return 'motto_mismatch';
        }
    }
}
