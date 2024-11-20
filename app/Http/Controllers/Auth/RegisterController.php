<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/feed';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // Método para mostrar o formulário de registro e gerar o código
    public function showRegistrationForm()
    {
        $habbo_code = bin2hex(random_bytes(5)); // Gera um código único para verificação na missão do Habbo
        return view('auth.register', compact('habbo_code'));
    }

    // Validação e verificação do código na missão do Habbo
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();
        $username = $data['name'];
        $habboCode = $data['habbo_code'];

        // Verifica se o código está na missão do Habbo
        $validationResult = $this->validateHabboCode($username, $habboCode);

        if ($validationResult === 'success') {
            // Cria o usuário após verificação bem-sucedida
            $user = $this->create($data);
            Auth::login($user);
            return redirect($this->redirectPath())->with('success', 'Usuário cadastrado com sucesso!');
        } elseif ($validationResult === 'profile_private') {
            return redirect()->back()->withErrors([
                'habbo_code' => 'Para confirmar o registro, é necessário liberar a visibilidade do perfil nas configurações do Habbo Hotel.',
            ]);
        } else {
            return redirect()->back()->withErrors([
                'habbo_code' => 'A missão não está igual ao código informado ou seu perfil do habbo é privado. Por favor, verifique e tente novamente.',
            ]);
        }
    }

    // Validação dos campos do formulário com mensagens personalizadas em português
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'habbo_code' => 'required|string',
        ], [
            'name.required' => 'O campo Nome de Usuário é obrigatório.',
            'name.unique' => 'Este nome de usuário já está cadastrado. Recupere sua senha!',
            'password.required' => 'O campo Senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'habbo_code.required' => 'É necessário fornecer o código da missão para confirmação.',
        ]);
    }

    // Criação do usuário após validação
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'password' => Hash::make($data['password']), // Certifique-se de que Hash::make é usado aqui
        ]);
    }

    // Método para validar o código na missão do Habbo
    protected function validateHabboCode($username, $code)
    {
        $url = "https://www.habbo.com.br/api/public/users?name=" . urlencode($username);

        try {
            $response = Http::withOptions([
                'verify' => false 
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();

                // Verifica se o perfil está visível
                if (isset($data['profileVisible']) && !$data['profileVisible']) {
                    return 'profile_private';
                }

                $motto = $data['motto'] ?? '';

                // Normaliza as strings para garantir a precisão na busca
                $normalizedMotto = strtolower(trim($motto));
                $normalizedCode = strtolower(trim($code));

                return strpos($normalizedMotto, $normalizedCode) !== false ? 'success' : 'motto_mismatch';
            } else {
                return 'motto_mismatch';
            }
        } catch (\Exception $e) {
            return 'motto_mismatch';
        }
    }
}
