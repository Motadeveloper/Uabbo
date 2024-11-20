@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Botão de Voltar -->
    <a href="../sorteios" class="btn-voltar">Voltar</a>
    <br>

    <div class="card-sorteio">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Sorteio</h2>
        
        <!-- Botão Participar do Sorteio alinhado à direita -->
        @auth
            @if(Auth::id() !== $sorteio->user_id && !$participando && !$sorteio->data_sorteio)
                <form action="{{ route('sorteios.participar', $sorteio->id) }}" method="POST" onsubmit="disableButton(this)">
                    @csrf
                    <button type="submit" class="btn btn-animado" id="participar-btn">Participar do Sorteio</button>
                </form>
            @elseif($participando && Auth::id() !== $sorteio->user_id)
                <button class="btn btn-success" disabled>Participando</button>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-animado">Participar do Sorteio</a>
        @endauth
    </div>

    <div class="d-flex mt-3">
        <!-- Avatar e Nickname do Autor -->
        <div class="author-info me-3 text-center">
            <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user={{ $sorteio->user->name }}&action=std&direction=2&head_direction=3&gesture=sml&size=l" 
                 alt="Avatar de {{ $sorteio->user->name }}" 
                 class="avatar-img">
            <p class="author-name">{{ strtoupper($sorteio->user->name) }}</p>
        </div>

        <!-- Conteúdo do Sorteio -->
        <div class="sorteio-content">
            <h3>{{ $sorteio->title }}</h3>
            <p>{{ $sorteio->description }}</p>

            <!-- Botões de Gerar e Cancelar Sorteio para o autor -->
            @auth
                @if(Auth::id() == $sorteio->user_id && !$sorteio->data_sorteio)
                    <form action="{{ route('sorteios.gerar', $sorteio->id) }}" method="POST" class="mt-2 d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning">Gerar Sorteio</button>
                    </form>
                    <form action="{{ route('sorteios.cancelar', $sorteio->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Cancelar Sorteio</button>
                    </form>
                @endif
            @endauth

            <!-- Mensagem de sorteio realizado -->
            @if($sorteio->data_sorteio)
            <div class="alert alert-warning mt-3 custom-alert d-flex align-items-center">
    <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEh1cbetxu16fENJhETUxRtgKRi0fbgsbv-RhNZjHGtFCJuo3o4hw1FrkDJ7_I-UudaKXk1yZ-tS0bSaau3zXDYx1ysGVP3XKaaHZWowtYNeiHbMoKiVpyI9uRd3YdCXqdjOvlBUJBSbSX6v/s0/ui_navigator_icon_roominfo.png" 
         alt="Ícone" class="alert-icon me-3">
    <div>
        Esse sorteio foi realizado em 
        {{ \Carbon\Carbon::parse($sorteio->data_sorteio)->timezone('America/Sao_Paulo')->format('d/m/Y') }} às 
        {{ \Carbon\Carbon::parse($sorteio->data_sorteio)->timezone('America/Sao_Paulo')->format('H:i') }}
    </div>
</div>

            @endif
    
        
            </div>
        </div>

        <!-- Número de participantes -->
        <p class="mt-3"><strong>{{ $sorteio->participantes->count() }} Participantes</strong></p>

        <!-- Lista de mini cards dos participantes com animação de esteira, oculto se não houver participantes -->
        @if($sorteio->participantes->count() > 0)
            <div class="carousel-container">
                <div class="carousel" id="carousel">
                    @foreach($sorteio->participantes as $participante)
                        <div class="mini-card text-center">
                            <img src="http://www.habbo.com.br/habbo-imaging/avatarimage?&user={{ $participante->name }}&size=m&head_direction=3&direction=2&headonly=1&img_format=png&gesture=sml&action=std"
                                 alt="Avatar de {{ $participante->name }}" class="rounded-circle">
                            <p class="participant-name">{{ strtoupper($participante->name) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Lista de Premiações, oculta após o sorteio -->
@if(!$sorteio->data_sorteio)
    <br><br>
    <h4>Premiações</h4>
    <ul class="list-group mb-3 premios-list">
        @foreach($sorteio->premios->sortBy('posicao') as $premio)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    @if($premio->posicao == 1)
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi4n2kXJJ2VYkyEL2Q8Tbvj6XIQ5EU63AMa8grgo9QFQF3iHKX5nOJk7g1rU-ALr9e2f7Y_qELl1Ap5cgKPV8ogVKkN9UcjkIbX9DNLO_Zp9nmmQwQvARSMO1eOeRsBm0pKCswx-2rE9uuQ/s0/fx_icon_111.png" alt="Primeiro Lugar" class="me-2" style="width: 29px; height: 36px;">
                    @elseif($premio->posicao == 2)
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhCxxpV9DnzzI9SDDaLxmpsVU_DgO5sZA8hpB2lKrS5ocPSijOrEjNAJJTnCFhxLGjWe8ccqjjHYoxQaFDlAanhGwrQUU7qm12PpS5tAyXrXxsOC3dmvkPyUkgZqFNVBDR0Qk4fWdmNxRJE/s0/fx_icon_110.png" alt="Segundo Lugar" class="me-2" style="width: 29px; height: 36px;">
                    @elseif($premio->posicao == 3)
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhJXk1Hw7BxtPsY3I2I6PBvfBM2kkKLNn16bm6OhsmsZNXehw-GPFHRg4HFcv154BWsy57RiaK1xnEblAmKMy6Mvh8hslV0ubko4GtJDQB2J9Y_WhiycLw9atcklSXlRtXMnFk_uGKgsmbg/s0/fx_icon_109.png" alt="Terceiro Lugar" class="me-2" style="width: 29px; height: 36px;">
                    @endif
                    <strong>{{ $premio->posicao }}º Lugar</strong>
                </div>
                <span class="d-flex align-items-center">
                    {{ $premio->premio_quantidade }}
                    @if($premio->premio_tipo == 'hc')
                        <span class="ms-2">{{ $premio->premio_quantidade > 1 ? 'HCs' : 'HC' }}</span>
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEitSGjGiPXmdduJZ331HTVpc6SWIgWo_OXbA9yNthxOp9upLFKZxG5rSRYn13kWtbjk3cnjOvutCeRfpgd3drUZZXyOU1zXz8yqbIhvba0BDU7pm6wla0azo-OoMvoovQRqNDANS1XCpL2y/s0/hc_icon_small.png" alt="HC" class="ms-2" style="width: 28px; height: 28px;">
                    @elseif($premio->premio_tipo == 'cambios')
                        <span class="ms-2">{{ $premio->premio_quantidade > 1 ? 'Câmbios' : 'Câmbio' }}</span>
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEj9j5dWGNIm5OBBMc_5tSPMgHiYIYpBzR-SyE0lgaR3ioPVSqT-jSaf0a9tpPn-xOm0W4DZ8VS0S9ruxDNqwKLqsrjrauo7EXzHVuQe0ycbxAfmE6bSc13zLZ73wByciI6Fd7hsyd-5CKFL/s0/money_small_coin.png" alt="Câmbios" class="ms-2" style="width: 22px; height: 22px;">
                    @endif
                </span>
            </li>
        @endforeach
    </ul>
@endif


        <!-- Exibir resultado do sorteio, caso tenha sido gerado -->
@if($sorteio->data_sorteio && isset($vencedores))
</br>
    <div class="card mb-3 resultado-sorteio">
        <div class="card-body">
            <h4>Resultado do Sorteio</h4>
            <p>
    <strong>Data do Sorteio:</strong> 
    {{ \Carbon\Carbon::parse($sorteio->data_sorteio)->timezone('America/Sao_Paulo')->format('d/m/Y') }} às 
    {{ \Carbon\Carbon::parse($sorteio->data_sorteio)->timezone('America/Sao_Paulo')->format('H:i') }}
</p>

            @foreach($vencedores as $detalhe)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Detalhes do ganhador -->
                    <div class="d-flex align-items-center">
                        <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user={{ $detalhe['user_name'] }}&size=m&head_direction=1&direction=1&headonly=0&img_format=png&gesture=sml&action=std" alt="{{ $detalhe['user_name'] }}" class="rounded-circle me-3">
                        
                        <!-- Ícones de posição -->
                        @if($detalhe['posicao'] == 1)
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi4n2kXJJ2VYkyEL2Q8Tbvj6XIQ5EU63AMa8grgo9QFQF3iHKX5nOJk7g1rU-ALr9e2f7Y_qELl1Ap5cgKPV8ogVKkN9UcjkIbX9DNLO_Zp9nmmQwQvARSMO1eOeRsBm0pKCswx-2rE9uuQ/s0/fx_icon_111.png" alt="Primeiro Lugar" class="me-2" style="width: 29px; height: 36px;">
                        @elseif($detalhe['posicao'] == 2)
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhCxxpV9DnzzI9SDDaLxmpsVU_DgO5sZA8hpB2lKrS5ocPSijOrEjNAJJTnCFhxLGjWe8ccqjjHYoxQaFDlAanhGwrQUU7qm12PpS5tAyXrXxsOC3dmvkPyUkgZqFNVBDR0Qk4fWdmNxRJE/s0/fx_icon_110.png" alt="Segundo Lugar" class="me-2" style="width: 29px; height: 36px;">
                        @elseif($detalhe['posicao'] == 3)
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhJXk1Hw7BxtPsY3I2I6PBvfBM2kkKLNn16bm6OhsmsZNXehw-GPFHRg4HFcv154BWsy57RiaK1xnEblAmKMy6Mvh8hslV0ubko4GtJDQB2J9Y_WhiycLw9atcklSXlRtXMnFk_uGKgsmbg/s0/fx_icon_109.png" alt="Terceiro Lugar" class="me-2" style="width: 29px; height: 36px;">
                        @endif

                        <!-- Detalhes do prêmio -->
                        <div>
                            <p><strong>{{ $detalhe['posicao'] }}º Lugar:</strong> {{ $detalhe['user_name'] }}</p>
                            <p><strong>Prêmio:</strong> {{ $detalhe['premio_quantidade'] }} 
                                @if($detalhe['premio_quantidade'] == 1)
                                    {{ $detalhe['premio_tipo'] == 'cambios' ? 'Câmbio' : 'HC' }}
                                @else
                                    {{ $detalhe['premio_tipo'] == 'cambios' ? 'Câmbios' : 'HCs' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <!-- Imagem do prêmio à direita -->
                    <div>
                        @if($detalhe['premio_tipo'] == 'cambios')
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgfgdDgifwwgRYMLBHJX3bo_q4ZES6tTNfy3ucNeZEe9PmktKEylAwwTsUDpuhP9qZh0b0f-jKC2ZqA33Wr8q2dgbEV169RwBHMBP7Kn1UQr-0KCF2BxyrH7iQ_lI5V0h2Fg1A4mPjGOzEm/s0/shop_money_04.png" alt="Câmbios" style="width: 100px; height: 100px;">
                        @elseif($detalhe['premio_tipo'] == 'hc')
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgHXKEzvry-2MFkQ8ysOTRg0ypzX1ijxBsCFaOIFkMxvqCe3S3bX2_gUaDyjNCujzpq3CubYXzOaR3CKYnIESeGj24k-0rw4b3tpv2tTBfh58HMXpIvVdjvuoQAOzafOgLvpT_94QHLAy7l/s0/shop_icon_hc.png" alt="HC" style="width: 78px; height: 90px;">
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

    </div>







    

   <!-- Card de Comentários para o Sorteio -->
<div class="card mt-4" style="background-color: #fff; border: none;">
    <div class="card-header" style="background-color: #fff; border: none;">
        <h4>Comentários para o Sorteio</h4>
    </div>
    <div class="card-body">
        <!-- Formulário para adicionar novo comentário -->
        @auth
            <form action="{{ route('sorteios.comentarios', $sorteio->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="comentario" class="form-control" rows="3" placeholder="Adicione um comentário..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Comentar</button>
            </form>
        @else
            <p class="text-muted">Faça <a href="{{ route('login') }}">login</a> para comentar.</p>
        @endauth

        <!-- Lista de comentários existentes -->
        <div class="mt-4">
            @foreach($sorteio->comentarios as $comentario)
                <div class="mb-3 d-flex align-items-start">
                    <!-- Imagem do autor -->
                    <img src="http://www.habbo.com.br/habbo-imaging/avatarimage?&user={{ $comentario->user->name }}&size=m&head_direction=3&direction=2&headonly=1&img_format=png&gesture=spk&action=std" 
                         alt="Avatar de {{ $comentario->user->name }}" 
                         class="rounded-circle me-3" 
                         style="width: 50px; height: 50px;">

                    <!-- Conteúdo do comentário -->
                    <div>
                        <strong>{{ strtoupper($comentario->user->name) }}</strong> 
                        <small class="text-muted">
                            {{ $comentario->created_at->timezone('America/Sao_Paulo')->format('d/m/Y') }} às {{ $comentario->created_at->timezone('America/Sao_Paulo')->format('H:i') }}
                        </small>
                        <p class="mb-0">{{ $comentario->conteudo }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>








<style>
    /* Card do sorteio */
    body {
        background-color:#f5f5f5;
    }
    .card-sorteio {
        position: relative;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        background-color: #ffffff;
        overflow: hidden;
    }
    .card-sorteio::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff6ec4, #7873f5, #42a5f5, #00c6ff, #ff6ec4);
        background-size: 200% 100%;
        animation: animateBottomBorder 3s linear infinite;
        border-radius: 0 0 8px 8px;
    }
    @keyframes animateBottomBorder {
        0% { background-position: 0% 50%; }
        100% { background-position: 100% 50%; }
    }
    .author-info { width: 100px; }
    .avatar-img { width: 100%; border-radius: 8px; }
    .author-name { margin-top: 5px; font-size: 14px; color: #333; text-transform: uppercase; text-align: center; }

    /* Botão animado para "Participar do Sorteio" */
    .btn-animado {
        background: linear-gradient(90deg, #ff6ec4, #7873f5, #42a5f5);
        color: #fff;
        border: none;
        padding: 10px 20px;
        animation: colorShift 3s linear infinite;
        border-radius: 5px;
    }
    .btn-animado:hover {
        background: linear-gradient(90deg, #42a5f5, #7873f5, #ff6ec4);
    }
    @keyframes colorShift {
        0% { background-position: 0%; }
        100% { background-position: 100%; }
    }

    /* Mini card de participantes e animação de esteira infinita */
    .carousel-container {
        overflow: hidden;
        max-width: 100%;
        border-radius: 8px;
        padding: 10px;
        background-color: #f9f9f9;
    }
    .carousel {
        display: flex;
        gap: 15px;
        animation: scrollCarousel 25s linear infinite;
    }
    .mini-card {
        width: 80px;
        text-align: center;
    }
    .mini-card img {
        width: 54px;
        height: 62px;
    }
    .participant-name {
        font-size: 12px;
        text-transform: uppercase;
    }

    @keyframes scrollCarousel {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

     /* Estilo para o botão de Voltar */
     .btn-voltar {
        display: inline-block;
        padding: 8px 16px;
        border: 1px solid #000;
        background-color: transparent;
        color: #000;
        text-decoration: none;
        border-radius: 4px;
        margin-bottom: 15px;
        transition: background-color 0.3s, color 0.3s;
    }

    .btn-voltar:hover {
        background-color: #000;
        color: #fff;
    }

    .premios-list .list-group-item {
        background-color: #ffffff;
    }

    .resultado-sorteio {
        background-color: #e0f8e0;
        border: none;
    }

    .custom-alert {
    background: linear-gradient(to bottom, #fdbc33 50%, #fed14a 50%); /* Fundo dividido em duas cores verticais */
    border: 2px solid #fda61b; /* Borda de 2px com cor personalizada */
    color: #fff; /* Cor da fonte branca */
    padding: 10px 15px; /* Espaçamento interno */
    border-radius: 8px; /* Bordas arredondadas */
    display: flex; /* Para alinhar o conteúdo e a imagem */
    align-items: center; /* Alinhamento vertical */
}

.alert-icon {
    width: 51px; /* Largura da imagem */
    height: 52px; /* Altura da imagem */
    margin-right: 15px; /* Espaço entre a imagem e o texto */
    flex-shrink: 0; /* Garante que a imagem não encolha */
}

</style>

<script>
function disableButton(form) {
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;
    button.innerText = 'Participando...';
}

</script>
@endsection
