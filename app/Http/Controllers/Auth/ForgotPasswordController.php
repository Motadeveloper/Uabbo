<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Mostra o formulário de solicitação de redefinição de senha.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        $habbo_code = bin2hex(random_bytes(5)); // Gera o código único para verificação na missão do Habbo
        session(['habbo_code' => $habbo_code]); // Armazena o código gerado na sessão
        return view('auth.passwords.email', compact('habbo_code'));
    }

    /**
     * Processa a redefinição de senha.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        // Validação dos campos
        $this->validator($request->all())->validate();

        $data = $request->all();
        $username = $data['name'];

        // Recupera o código gerado na sessão
        $habboCode = session('habbo_code');

        // Valida o código na missão do Habbo
        $validationResult = $this->validateHabboCode($username, $habboCode);

        if ($validationResult['status'] === 'success') {
            // Localiza o usuário pelo nome
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
        } elseif ($validationResult['status'] === 'profile_private') {
            return redirect()->back()->withErrors([
                'name' => 'Para confirmar a redefinição, é necessário liberar a visibilidade do perfil nas configurações do Habbo Hotel.',
            ]);
        } else {
            return redirect()->back()->withErrors([
                'name' => 'A missão no perfil do Habbo não corresponde ao código gerado. Verifique e tente novamente.',
            ]);
        }
    }

    /**
     * Validação dos campos do formulário.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|exists:users,name',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'O campo Nome de Usuário é obrigatório.',
            'name.exists' => 'O usuário informado não foi encontrado.',
            'password.required' => 'O campo Senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ]);
    }

    /**
     * Valida o código na missão do Habbo.
     *
     * @param string $username
     * @param string $code
     * @return array
     */
    protected function validateHabboCode($username, $code)
    {
        $url = "https://www.habbo.com.br/api/public/users?name=" . urlencode($username);

        try {
            $response = Http::withOptions(['verify' => false])->get($url);

            $data = $response->json();

            // Verifica se o perfil está visível
            if (isset($data['profileVisible']) && !$data['profileVisible']) {
                return [
                    'status' => 'profile_private',
                ];
            }

            $motto = strtolower(trim($data['motto'] ?? ''));
            $normalizedCode = strtolower(trim($code));

            return [
                'status' => strpos($motto, $normalizedCode) !== false ? 'success' : 'motto_mismatch',
                'motto' => $data['motto'] ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }
}
