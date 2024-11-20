@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/feed/home.css') }}">


<div class="container">


    <body data-user-id="{{ Auth::id() }}">
    <h2>Feed</h2>

    @if(session('success'))
    <div class="alert-success-custom d-flex align-items-center p-3">
        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi0-ZOqHJwpE2QNLZfWi5jH7KqdztKjQic7_Q7EWtHIehNsABn2Uh884b5KUh_mBHbhDiiAxWh0X5odT64laka0O7r_LpjaYW4VAexdR3iwYTnfbVlf4tHr-zetwJBIp-5FemDQ-9TPe48r/s0/ui_navigator_icon_fav_on.png" 
             alt="Ícone de Sucesso" style="width: 44px; height: 40px; margin-right: 10px;">
        <span>{{ session('success') }}</span>
    </div>
@endif


  <!-- Caixa de criação de tópico com botão de minimizar -->
<div class="card mb-4 p-3" id="createTopicBox">
    <button class="btn-close-custom" onclick="toggleCreateTopicBox()"></button>
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">O que você está pensando?</h4>
    </div>
    <div id="createTopicContent" class="mt-3">

        <!-- Formulário de Divulgar Quarto -->
        <div id="promoteRoomForm" style="display: none;" class="card mb-4 p-3">
            <h5>Divulgar Quarto do Habbo</h5>
            <div class="d-flex">
                <!-- Imagem do usuário à esquerda -->
                @auth
                    <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user={{ Auth::user()->name }}&action=std&direction=2&head_direction=3&gesture=sml&size=l" alt="Avatar do usuário" class="rounded-circle me-3" width="70" height="120">
                @else
                    <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=4queijos&action=std&direction=2&head_direction=3&gesture=sml&size=l" alt="Avatar padrão do Habbo" class="rounded-circle me-3" width="70" height="120">
                @endauth

                <div class="form-group w-100">
                    <label for="roomId">ID do Quarto:</label>
                    <input type="text" id="roomId" class="form-control" placeholder="Digite o ID do quarto">
                    <button type="button" class="btn btn-primary mt-2" onclick="{{ Auth::check() ? 'fetchRoomData()' : 'redirectToLogin()' }}">Buscar Quarto</button>
                    <button type="button" class="btn btn-secondary mt-2" onclick="cancelPromotion()">Cancelar</button>
                </div>
            </div>
        </div>

        <!-- Formulário de Denúncia do Habbo -->
        <div id="reportHabboForm" style="display: none;" class="card mb-4 p-3">
            <h5>Denunciar Habbo</h5>
            <div class="d-flex">
                <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhtap5n0qy6gLx4HIJQrnLrfUqfDM-DTbN4m8ye50xG-Y4JusdkL4TK59Oy43qmkr4Ig8eBwTYZNKbnsG7Z0VYdHENWgvaCj8pz3-vIjVS3MoXm5RPqUOmgfxTUhjZc1p6mWoxmRCDSTh0e/s0/icon_small_notification.png" alt="Ícone de Denúncia" style="width: 20px; height: 21px; margin-right: 8px;">
                <div class="form-group w-100">
                    <label for="habboNickname">Nickname do Habbo:</label>
                    <input type="text" id="habboNickname" class="form-control" placeholder="Digite o nickname do Habbo">
                </div>
            </div>
            <div class="form-group mt-2">
                <label for="reportReason">Motivo da Denúncia:</label>
                <textarea id="reportReason" class="form-control" rows="3" placeholder="Explique o motivo da denúncia"></textarea>
            </div>
            <button type="button" class="btn btn-primary mt-2" onclick="{{ Auth::check() ? 'fetchHabboData()' : 'redirectToLogin()' }}">Buscar Informações</button>
            <button type="button" class="btn btn-secondary mt-2" onclick="cancelReport()">Cancelar</button>
        </div>

        <!-- Conteúdo principal -->
        <div class="d-flex">
            <!-- Imagem do usuário -->
            @auth
                <img id="userAvatar" src="https://www.habbo.com.br/habbo-imaging/avatarimage?user={{ Auth::user()->name }}&action=std&direction=2&head_direction=3&gesture=sml&size=l" alt="Avatar do usuário" class="rounded-circle" width="70" height="120">
            @else
                <img id="userAvatar" src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=4queijos&action=std&direction=2&head_direction=3&gesture=sml&size=l" alt="Avatar padrão do Habbo" class="rounded-circle" width="70" height="120">
            @endauth

            <form id="createTopicForm" method="POST" action="{{ route('topics.store') }}" class="w-100 ms-3">
                @csrf
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" maxlength="800" required placeholder="Digite seu tópico aqui" oninput="updateCharacterCount(this)"></textarea>
                    <small id="charCount" class="text-muted">800 caracteres restantes</small>
                </div>
                <div style="display: flex; gap: 8px;">
                    @auth
                        <button type="submit" class="btn btn-success mt-2" style="display: flex; align-items: center;" id="publishButton" disabled>
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgpq8cJIhKuB23UP9ou4kjnSDMflGC_rlsn_7t2iU2NztSi4EV4Gc_ckmP2n2_nlRl8c8g0Cntce7o3qbz2g9v2_9eGOszXhntOW72uB2-URGyh_hFOCNxKoZcdXuIujBmHzHDk4R1i2EK2/s0/fx_icon_114.png" alt="Ícone de Postar" style="width: 32px; height: 29px; margin-right: 8px;">
                            Postar
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success mt-2" style="display: flex; align-items: center;">
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgpq8cJIhKuB23UP9ou4kjnSDMflGC_rlsn_7t2iU2NztSi4EV4Gc_ckmP2n2_nlRl8c8g0Cntce7o3qbz2g9v2_9eGOszXhntOW72uB2-URGyh_hFOCNxKoZcdXuIujBmHzHDk4R1i2EK2/s0/fx_icon_114.png" alt="Ícone de Postar" style="width: 32px; height: 29px; margin-right: 8px;">
                            Postar
                        </a>
                    @endauth

                    <button type="button" class="btn btn-yellow mt-2" onclick="{{ Auth::check() ? 'togglePromoteRoomForm()' : 'redirectToLogin()' }}" style="background-color: #ffc107; border-color: #ffc107; color: #212529; display: flex; align-items: center;">
                        <img src="https://www.habboassets.com/images/catalog-icons/55" alt="Ícone" style="width: 18px; height: 18px; margin-right: 8px;">
                        Divulgar Quarto
                    </button>
                    <button type="button" class="btn btn-danger mt-2" onclick="{{ Auth::check() ? 'toggleReportHabboForm()' : 'redirectToLogin()' }}" style="display: flex; align-items: center;">
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhtap5n0qy6gLx4HIJQrnLrfUqfDM-DTbN4m8ye50xG-Y4JusdkL4TK59Oy43qmkr4Ig8eBwTYZNKbnsG7Z0VYdHENWgvaCj8pz3-vIjVS3MoXm5RPqUOmgfxTUhjZc1p6mWoxmRCDSTh0e/s0/icon_small_notification.png" alt="Ícone de Denúncia" style="width: 20px; height: 21px; margin-right: 8px;">
                        Denunciar Habbo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



    <!-- Exibir os tópicos -->
    
    <div id="topicsContainer">
    <!-- Os tópicos serão carregados dinamicamente aqui -->
</div>

<div id="loadingIndicator" class="text-center mt-4" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Carregando...</span>
    </div>
</div>
    
        

                        
    </div>
</div>




<script src="{{ asset('js/feed/home.js') }}"></script>
@endsection
