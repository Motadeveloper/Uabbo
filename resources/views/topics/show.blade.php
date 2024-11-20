@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tópico</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @php
        // Calcula o número total de comentários, incluindo as respostas
        $totalComments = $topic->comments->count() + $topic->comments->sum(fn($comment) => $comment->replies->count());
    @endphp

    <!-- Exibir o tópico específico -->
    <div class="card mb-3 p-3" id="topic-{{ $topic->id }}">
        <div class="d-flex">
            <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user={{ $topic->user->name }}&size=l" alt="Avatar do usuário" class="rounded-circle" width="70" height="120">
            <div class="ms-3" style="max-width: 100%;">
                <h6 class="fw-bold text-uppercase">{{ $topic->user->name }}</h6>
                <p class="card-text" style="max-width: 100%; word-break: break-word;">{!! $topic->content !!}</p>
                <p class="text-muted small">Publicado em {{ $topic->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>

                <!-- Informações adicionais do tópico -->
                <div class="d-flex mt-2 text-muted small">
                    <span class="me-3"><i class="far fa-comments"></i> {{ $totalComments }} Comentários</span>
                    <span class="me-3"><i class="far fa-eye"></i> {{ $topic->views }} Visualizações</span>
                </div>

                <!-- Caixa de link para copiar -->
                <div class="input-group mt-2" style="max-width: 300px;">
                    <input type="text" class="form-control" id="link-{{ $topic->id }}" value="{{ url('/topics/'.$topic->id) }}" readonly>
                    <button class="btn btn-outline-secondary" id="copyBtn-{{ $topic->id }}" onclick="copyLink('link-{{ $topic->id }}', 'copyBtn-{{ $topic->id }}')">Copiar link</button>
                </div>

                <!-- Botões de controle do tópico -->
                <div class="d-flex mt-3">
                    @if(Auth::id() === $topic->user_id)
                        <div id="deleteConfirm-{{ $topic->id }}" style="display: none;">
                            <p class="text-danger">Tem certeza que deseja apagar este tópico?</p>
                            <form action="{{ route('topics.destroy', $topic->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Confirmar Exclusão</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="toggleDeleteConfirm({{ $topic->id }}, false)">Cancelar</button>
                            </form>
                        </div>
                        <button type="button" id="deleteButton-{{ $topic->id }}" class="btn btn-outline-danger btn-sm" onclick="toggleDeleteConfirm({{ $topic->id }}, true)">Apagar</button>
                    @endif
                    <button type="button" class="btn btn-outline-primary btn-sm ms-2" onclick="toggleReplyBox('topic-{{ $topic->id }}')">Responder</button>
                </div>

                <!-- Caixa de resposta para o tópico -->
                <div id="replyBox-topic-{{ $topic->id }}" class="mt-2" style="display: none; max-width: 100%;">
                    @auth
                        <form method="POST" action="{{ route('comments.store', $topic->id) }}">
                            @csrf
                            <textarea name="content" class="form-control" rows="2" maxlength="300" required placeholder="Digite seu comentário" style="width: 100%;"></textarea>
                            <button type="submit" class="btn btn-outline-primary mt-2">Comentar</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Exibir comentários do tópico -->
    <div class="comments-section mt-3">
        <h6>Comentários</h6>
        @foreach($topic->comments as $comment)
            <div class="d-flex align-items-start mb-2">
                <img src="http://www.habbo.com.br/habbo-imaging/avatarimage?&user={{ $comment->user->name }}&size=m&head_direction=2&direction=2&headonly=1&img_format=png&gesture=spk&action=std" alt="Avatar do usuário" class="rounded-circle me-2" width="40" height="40">
                <div class="comment-box" style="max-width: 100%;">
                    <h6 class="fw-bold text-uppercase">{{ $comment->user->name }}</h6>
                    <p style="word-break: break-word;">{{ $comment->content }}</p>
                    <p class="text-muted small">{{ $comment->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>

                    <!-- Botão de "Responder" para comentários -->
                    <button class="btn btn-link text-decoration-none" onclick="toggleReplyBox('comment-{{ $comment->id }}')">Responder</button>
                    <div id="replyBox-comment-{{ $comment->id }}" class="mt-2" style="display: none; max-width: 100%;">
                        @auth
                            <form method="POST" action="{{ route('comments.reply', $comment->id) }}">
                                @csrf
                                <textarea name="content" class="form-control" rows="2" maxlength="300" required placeholder="Digite sua resposta" style="width: 100%;"></textarea>
                                <button type="submit" class="btn btn-outline-primary mt-2">Responder</button>
                            </form>
                        @endauth
                    </div>

                    <!-- Exibir respostas ao comentário -->
                    @foreach($comment->replies as $reply)
                        <div class="ms-5 mt-3 d-flex align-items-start">
                            <img src="http://www.habbo.com.br/habbo-imaging/avatarimage?&user={{ $reply->user->name }}&size=m&head_direction=2&direction=2&headonly=1&img_format=png&gesture=spk&action=std" alt="Avatar do usuário" class="rounded-circle me-2" width="40" height="40">
                            <div class="comment-box" style="max-width: 100%;">
                                <h6 class="fw-bold text-uppercase">{{ $reply->user->name }}</h6>
                                <p style="word-break: break-word;">{{ $reply->content }}</p>
                                <p class="text-muted small">{{ $reply->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function toggleReplyBox(replyId) {
        const replyBox = document.getElementById(`replyBox-${replyId}`);
        replyBox.style.display = replyBox.style.display === 'none' ? 'block' : 'none';
    }

    function toggleDeleteConfirm(topicId, showConfirm) {
        const confirmBox = document.getElementById(`deleteConfirm-${topicId}`);
        const deleteButton = document.getElementById(`deleteButton-${topicId}`);
        confirmBox.style.display = showConfirm ? 'block' : 'none';
        deleteButton.style.display = showConfirm ? 'none' : 'inline-block';
    }

    function copyLink(inputId, buttonId) {
        const linkInput = document.getElementById(inputId);
        linkInput.select();
        document.execCommand("copy");

        const copyButton = document.getElementById(buttonId);
        copyButton.textContent = "Copiado";

        setTimeout(() => {
            copyButton.textContent = "Copiar link";
        }, 2000);
    }
</script>

<style>
    /* Estilos reutilizados do home.blade.php para a página do tópico */
    .toggle-comments-btn {
        margin: 0 auto;
    }
    body {
        background-color:#f5f5f5;
    }
    .comment-box {
        max-width: 100%;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .hierarchy-line {
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        border-left: 2px solid #ccc;
    }

    .comment-container {
        width: 100%;
    }

    /* Responsivo */
    @media (max-width: 576px) {
        .d-flex.align-items-start {
            flex-direction: column !important;
            align-items: flex-start;
        }
    }
</style>
@endsection
