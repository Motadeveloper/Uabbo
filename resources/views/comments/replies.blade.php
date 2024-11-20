@foreach($comments as $comment)
    <div class="d-flex align-items-start mt-3 position-relative comment-container">
        <!-- Linha de encadeamento vertical para indicar a hierarquia -->
        @if($comment->parent_id)
            <div class="hierarchy-line"></div>
        @endif

        <!-- Imagem do autor da resposta -->
        <img src="http://www.habbo.com.br/habbo-imaging/avatarimage?&user={{ $comment->user->name }}&size=m&head_direction=2&direction=2&headonly=1&img_format=png&gesture=spk&action=std" 
             alt="Avatar do usuário" 
             class="rounded-circle me-2 comment-avatar" 
             width="50" 
             height="50">

        <div class="comment-box">
            <h6 class="fw-bold text-uppercase">{{ $comment->user->name }}</h6>
            <p style="word-break: break-word;">{{ $comment->content }}</p>
            <p class="text-muted small">{{ $comment->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>

            <!-- Botão de "Responder" -->
            <button class="btn btn-link text-decoration-none p-0" onclick="toggleReplyBox('comment-{{ $comment->id }}')">Responder</button>
            <div id="replyBox-comment-{{ $comment->id }}" class="mt-2" style="display: none;">
                @auth
                    <form method="POST" action="{{ route('comments.reply', $comment->id) }}">
                        @csrf
                        <textarea name="content" class="form-control" rows="2" maxlength="300" required placeholder="Digite sua resposta" style="width: 100%;"></textarea>
                        <button type="submit" class="btn btn-outline-primary mt-2">Responder</button>
                    </form>
                @endauth
            </div>

            <!-- Renderizar sub-respostas sem deslocamento para a direita -->
            <div class="replies mt-3">
                @include('comments.replies', ['comments' => $comment->replies])
            </div>
        </div>
    </div>
@endforeach

<!-- CSS inline para estilizar a linha de hierarquia e corrigir o layout no modo responsivo -->
<style>
    /* Linha vertical para indicar hierarquia */
    .hierarchy-line {
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        border-left: 2px solid #ccc;
    }

    /* Garantir que os comentários e respostas fiquem dentro da box */
    .comment-container {
        width: 100%;
        max-width: 100%;
    }

    .comment-box {
        max-width: 100%;
        margin-top: 10px;
        padding-left: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
        overflow-wrap: break-word;
    }

    /* Definir largura máxima para respostas e impedir que se desloquem para a direita */
    .replies .comment-box {
        max-width: 100%; /* Limita a largura das respostas para se manter dentro do contêiner principal */
        margin-left: 0; /* Remove o recuo */
    }

    /* Correção para responsivo: garantir que tudo fique alinhado verticalmente em telas pequenas */
    @media (max-width: 576px) {
        .d-flex.align-items-start {
            flex-direction: column !important;
            align-items: flex-start;
        }
        .comment-avatar {
            margin-right: 0; /* Remove margem direita do avatar */
            margin-bottom: 5px; /* Adiciona espaço abaixo do avatar */
        }
        .hierarchy-line {
            display: none; /* Oculta linha de hierarquia em telas pequenas */
        }
    }
</style>
