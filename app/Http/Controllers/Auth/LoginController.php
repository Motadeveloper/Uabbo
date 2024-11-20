<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Onde redirecionar os usuários após o login.
     *
     * @var string
     */
    protected $redirectTo = '/feed';

    /**
     * Cria uma nova instância do controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Use o campo "name" para autenticação.
     *
     * @return string
     */
    public function username()
    {
        return 'name';
    }

    /**
     * Redirecionar após login com mensagem de sucesso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        Log::info('Usuário autenticado com sucesso', [
            'user_id' => $user->id,
            'name' => $user->name,
            'ip' => $request->ip(),
        ]);

        $request->session()->flash('success', 'Login realizado com sucesso! :)');
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Tentativa de login com logs detalhados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        // Logar os dados recebidos no login (sem senha para segurança)
        Log::info('Tentativa de login', [
            'name' => $request->input('name'),
            'ip' => $request->ip(),
        ]);

        // Verificar se os dados fornecidos correspondem ao que está no banco
        $credentials = $this->credentials($request);
        Log::debug('Credenciais recebidas para autenticação', $credentials);

        // Realizar a tentativa de autenticação
        $loginSuccess = $this->guard()->attempt(
            $credentials,
            $request->filled('remember')
        );

        // Logar o resultado da tentativa
        if ($loginSuccess) {
            Log::info('Login bem-sucedido', ['name' => $credentials['name']]);
        } else {
            Log::warning('Login falhou. Credenciais inválidas.', $credentials);
        }

        return $loginSuccess;
    }

    /**
     * Resposta em caso de falha no login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        Log::warning('Falha no login', [
            'name' => $request->input('name'),
            'ip' => $request->ip(),
        ]);

        return redirect()->back()
            ->withInput($request->only('name', 'remember'))
            ->withErrors([
                'login' => 'Usuário ou senha incorretos.',
            ]);
    }
}
