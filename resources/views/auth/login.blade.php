<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uabbo | Uma Rede Social de Pixels</title>

    <!-- JS & jQuery -->
    <script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');

    * {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        color: #fff;
    }

    p {
        cursor: pointer;
        font-weight: 600;
        color: slateblue;
    }

    h1 {
        font-size: 1.8em;
        color: slateblue;
        padding: 0px 0px 35px 15px;
    }

    h2 {
        font-size: 1.4em;
        color: slateblue;
    }

    input {
        width: 80%;
        height: 30px;
        border: none;
        border-bottom: 1px solid silver;
        outline: none;
        font-weight: 600;
        color: #1c1c1c;
        padding-left: 3px;
    }

    button {
        cursor: pointer;
        width: 120px;
        height: 35px;
        border: none;
        border-radius: 5px;
        background: slateblue;
    }

    body {
        width: 100%;
        height: 100vh;
        background-color: slateblue;
        background-image: url('../img/fundo.png');
        background-repeat: no-repeat;
        background-size: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #container {
        width: 320px;
        height: 520px;
        border-radius: 10px;
        -webkit-box-shadow: 0px 0px 6px -1px #000000; 
        box-shadow: 0px 0px 6px -1px #000000;
        background: #785eef;
        display: flex;
        align-items: center;
    }   

    #container .banner {
        width: 520px;
        height: 520px;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        background: #785eef;
        display: none;
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;
    }

    #container .box-login {
        width: 320px;
        height: 520px;
        border-radius: 10px;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .box-login .box {
        width: 80%;
        height: 320px;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    .box .social {
        width: 240px;
        height: 42px;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }

    .social img {
        cursor: pointer;
    }

    .box-login .box-account {
        width: 80%;
        height: 360px;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    #bubble {
        cursor: pointer;
        position: absolute;
        width: 50px;
        height: 50px;
        right: 15px;
        bottom: 15px;
        border-radius: 50%;
        border: 1px solid #483D8B;
        background: slateblue;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    @media (min-width: 1024px) {
        #container {
            width: 902px;
            justify-content: space-between;
        }  

        #container .banner {
            display: flex;
        }

        #container .box-login {
            width: 385px;
            border-radius: 10px 10px 10px 0px;
        }
    }

    .avatar-preview {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        margin-right: 10px;
        display: inline-block;
        vertical-align: middle;
    }
    .alert {
        width: 80%;
        padding: 10px;
        margin: 10px auto;
        text-align: center;
        border-radius: 5px;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .timeline {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        background-color: red;
        animation: countdown 5s linear forwards;
    }
    @keyframes countdown {
        from { width: 100%; }
        to { width: 0; }
    }
</style>

<body>
    <div id="container">
        <div class="banner">
            <img src="{{ asset('img/image_event_settings.png') }}" alt="imagem-login">
            <p style="color: #fff; font-weight: 400;">
                Seja bem-vindo(a), A Uabbo é uma rede social do Habbo Hotel Brasil.
                <br>Aqui, você pode interagir com outros jogadores, trocar dicas,
                <br>organizar sorteios e promover seus quartos e mobílias.
            </p>
        </div>
        
        <div class="box-login">
            <h1>
                Seja Bem-vindo à Uabbo.
            </h1>

            @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div id="errorAlert" class="alert alert-danger" style="display: flex; align-items: center; position: relative;">
        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjeOhbiIn9cOEGvv2IqJGmHsWVT-DYaVSO2ZSCvwawCpImW7JAnkrutR4hi0M6Vl6-TukhBwuvsHg1TvsHUGskKyqVLn1DrOKNdPGNjo8DV06NBIH9AyUyhE-f6sE8vP-6kPR9ckRHNvgfv/s0/icon_cancel.png" 
             alt="Erro" style="width: 20px; height: 20px; margin-right: 8px;">
        {{ $errors->first() }}
        
        <!-- Linha de tempo para animação -->
        <div class="timeline"></div>
    </div>
@endif
            <div class="box">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <!-- Avatar do usuário em tempo real -->
                    <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=4queijos&action=std&direction=2&head_direction=3&gesture=sml&size=l" 
                         alt="avatar do usuário" id="userAvatar" class="avatar-preview" style="width: 64px; height: 110px;">
                    <h2>Faça o seu login agora</h2>
                </div>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Corrigido: Adiciona o ID "username" para ser usado no JavaScript -->
                    <input type="text" class="form-control" name="name" id="username" placeholder="Usuário" required>
                    <input type="password" class="form-control" name="password" placeholder="Senha" required>
                    <br><br>
                    <a href="{{ route('password.request') }}">
                        <p>Esqueceu a sua senha?</p>
                        </br>
                    </a>
                    
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                
                <a href="{{ route('register') }}">
                    <p>Criar uma conta</p>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const usernameInput = document.getElementById("username");
            const userAvatar = document.getElementById("userAvatar");

            if (usernameInput && userAvatar) {
                usernameInput.addEventListener("input", function() {
                    const username = usernameInput.value.trim();
                    if (username) {
                        userAvatar.src = `https://www.habbo.com.br/habbo-imaging/avatarimage?user=${username}&action=std&direction=2&head_direction=3&gesture=sml&size=l`;
                    } else {
                        userAvatar.src = "https://www.habbo.com.br/habbo-imaging/avatarimage?user=4queijos&action=std&direction=2&head_direction=3&gesture=sml&size=l";
                    }
                });
            } else {
                console.error("Elemento com ID 'username' ou 'userAvatar' não encontrado.");
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
        const errorAlert = document.getElementById("errorAlert");
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.transition = "opacity 0.5s";
                errorAlert.style.opacity = "0";
                setTimeout(() => errorAlert.remove(), 500); // Remove o elemento após a transição
            }, 5000);
        }
    });
    </script>    
</body>
</html>
