<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uabbo | Crie sua conta social na Uabbo</title>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <script type="text/javascript" src="js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>

    <div id="container">
        <div class="banner">
         
        <img src="{{ asset('img/image_event_settings.png') }}" alt="imagem-login">
            <p style="color: #fff;">
                Faça parte da Uabbo, a rede social dos fãs do Habbo.
                    <br>   Conecte-se com outros jogadores, compartilhe estratégias,
                <br>participe de eventos e divulgue seus quartos e coleções de mobis.
            </p>
        </div>
        
        <div class="box-login">
        
            
            <h1>Junte-se a nós,<br>Crie hoje a sua conta!</h1>

            <div class="box-account">
                
                <!-- Formulário de Registro -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="instruction-card">
                    <p>Coloque o seguinte código em sua missão no Habbo antes de enviar:</p>
                     <div class="code-display">
                      <strong>{{ $habbo_code }}</strong>
                        </div>
                        </div>

                    <!-- Nome de Usuário do Habbo -->
                    
                    <div class="form-group">
                    <input type="text" name="name" id="username" placeholder="Nome de Usuário do Habbo" class="form-control" required autocomplete="username">
                        
                    </div>

                    <!-- Senha -->
                    <div class="form-group">
                        <input type="password" name="password" id="password" placeholder="Senha" class="form-control" required autocomplete="new-password">
                        
                    </div>

                    <!-- Confirmação de Senha -->
                    <div class="form-group">
                        <input type="password" name="password_confirmation" id="cpassword" placeholder="Confirmar a Senha" class="form-control" required autocomplete="new-password">
                    </div>

                    <!-- Código Habbo (campo oculto) -->
                    <input type="hidden" name="habbo_code" value="{{ $habbo_code }}">

                    <!-- Botão de Enviar -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                    <!-- Avatar do usuário em tempo real -->
                    <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=4queijos&action=std&direction=2&head_direction=3&gesture=sml&size=l" 
                         alt="avatar do usuário" id="userAvatar" class="avatar-preview" style="width: 64px; height: 110px;">
                    <button type="submit" class="btn btn-primary">Criar conta</button>
                </form>
            </div>
        </div>
    </div>
    <div id="alertModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p id="alertMessage"></p>
        <div class="timer-bar" id="timerBar"></div>
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
    document.addEventListener("DOMContentLoaded", function () {
    function showModal(message, isError = false) {
        const modal = document.getElementById("alertModal");
        const alertMessage = document.getElementById("alertMessage");
        const timerBar = document.getElementById("timerBar");

        // Configura a mensagem e estilo
        alertMessage.textContent = message;
        alertMessage.style.color = isError ? "#721c24" : "#155724";
        timerBar.style.backgroundColor = isError ? "#f8d7da" : "#d4edda";

        // Exibe o modal
        modal.style.display = "flex";

        // Esconde o modal após 5 segundos
        setTimeout(() => {
            modal.style.display = "none";
        }, 5000);
    }

    // Exemplo de uso ao carregar a página com uma mensagem de erro/sucesso
    @if (session('success'))
        showModal("{{ session('success') }}");
    @elseif ($errors->any())
        showModal("{{ $errors->first() }}", true);
    @endif
});
    </script>
    
</body>
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
        font-size: 1.7em;
        color: slateblue;
        padding: 0px 0px 5px 5px;
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
        padding: 5px;
        margin: 5px auto;
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
    .instruction-card {
    background-color: #9932CC; /* Fundo amarelo */
    color: #fff; /* Texto em cinza escuro */
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    margin: 20px auto;
    text-align: center;
}

.instruction-card p {
    margin-bottom: 10px;
    font-weight: 100;
    color: #ffffff;
}

.code-display {
    background-color: #9400D3; /* Fundo para o código */
    color: #000000; /* Cor do texto do código */
    padding: 10px;
    border-radius: 5px;
    font-size: 1.2em;
    font-family: monospace;
}
.modal {
    position: fixed; /* Fixo na tela */
    top: 50%; /* Centraliza verticalmente */
    left: 50%; /* Centraliza horizontalmente */
    transform: translate(-50%, -50%); /* Ajusta para centralização exata */
    width: 100%;
    max-width: 400px; /* Largura máxima do modal */
    background-color: rgba(0, 0, 0, 0.5); /* Fundo escurecido */
    z-index: 1000; /* Sobrepõe outros elementos */
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
    border-radius: 8px;
}

.modal-content {
    background-color: #fff;
    color: #333;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
    text-align: center;
    position: relative;
}

/* Estilo da barra de tempo */
.timer-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    background-color: #4caf50; /* Cor da barra do temporizador */
    animation: countdown 5s linear forwards;
}

/* Animação para a barra de tempo */
@keyframes countdown {
    from { width: 100%; }
    to { width: 0; }
}
</style>
</html>
