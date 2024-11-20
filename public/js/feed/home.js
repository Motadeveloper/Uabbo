    function updateCharacterCount(textarea) {
        const maxChars = 800;
        const charCount = maxChars - textarea.value.length;
        document.getElementById('charCount').textContent = `${charCount} caracteres restantes`;
        document.getElementById('publishButton').disabled = charCount < 0 || charCount === maxChars;
    }

    function toggleDeleteConfirm(topicId, showConfirm) {
        const confirmBox = document.getElementById(`deleteConfirm-${topicId}`);
        const deleteButton = document.getElementById(`deleteButton-${topicId}`);
        const replyButton = document.getElementById(`replyButton-${topicId}`);
        
        confirmBox.style.display = showConfirm ? 'block' : 'none';
        deleteButton.style.display = showConfirm ? 'none' : 'inline-block';
        replyButton.style.display = showConfirm ? 'none' : 'inline-block';
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

    function toggleCreateTopicBox() {
        const createTopicContent = document.getElementById('createTopicContent');
        const toggleButton = document.querySelector('#createTopicBox button');

        if (createTopicContent.style.display === 'none' || createTopicContent.style.display === '') {
            createTopicContent.style.display = 'block';
            toggleButton.textContent = '-';
        } else {
            createTopicContent.style.display = 'none';
            toggleButton.textContent = '+';
        }
    }

    






    function togglePromoteRoomForm() {
        // Ocultar elementos relacionados ao botão "Denunciar Habbo"
        document.querySelector('button[onclick="toggleReportHabboForm()"]').style.display = 'none';
    
        // Exibir o formulário "Divulgar Quarto"
        document.getElementById('promoteRoomForm').style.display = 'block';
    
        // Ocultar elementos relacionados ao botão "Divulgar Quarto"
        document.querySelector('button[onclick="togglePromoteRoomForm()"]').style.display = 'none';
        document.querySelector('textarea[name="content"]').style.display = 'none';
        document.getElementById('publishButton').style.display = 'none';
        document.getElementById('charCount').style.display = 'none';
        document.getElementById('userAvatar').style.display = 'none';
    }




let page = 1;
const perPage = 7;
let loading = false;
const topicsContainer = document.getElementById("topicsContainer");
const loadingIndicator = document.getElementById("loadingIndicator");

// Armazena todos os comentários carregados para cada tópico
let allComments = {};

// Carregar tópicos
function fetchTopics() {
    if (loading) return; // Evita chamadas simultâneas
    loading = true;
    loadingIndicator.style.display = "block";

    fetch(`/api/feed?page=${page}&perPage=${perPage}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro ao carregar tópicos: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            loadingIndicator.style.display = "none";
            loading = false;

            // Verifica se há tópicos a serem carregados
            if (!data.data || data.data.length === 0) {
                console.log("Nenhum tópico encontrado.");
                return;
            }

            // Adiciona cada tópico ao contêiner
            data.data.forEach(topic => {
                const topicHtml = `
<div class="topic-card card mb-3 p-3" id="topic-${topic.id}">
    <div class="d-flex">
        <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${topic.user.name}&size=l" 
             alt="Avatar do usuário" class="rounded-circle" width="70" height="120">
        <div class="ms-3" style="max-width: 100%;">
            <h6 class="fw-bold text-uppercase">${topic.user.name}</h6>
            <p class="card-text" style="word-break: break-word;">${topic.content}</p>
            <p class="text-muted small">Publicado em ${new Date(topic.created_at).toLocaleString("pt-BR")}</p>
            <div class="d-flex mt-2 text-muted small">
                <span id="comments-count-${topic.id}">${topic.total_comments_with_replies || 0} Comentários</span>
            </div>

            <!-- Botão de Responder, visível apenas para usuários logados -->
            ${window.isAuthenticated ? `
            <button class="btn btn-sm btn-primary mt-2" onclick="toggleReplyBox(${topic.id})">Responder</button>` : ''}

            <!-- Botão de Visualizar Comentários (se houver comentários) -->
            ${topic.total_comments_with_replies > 0 ? `
            <a href="javascript:void(0);" class="toggle-comments-link" id="toggle-comments-${topic.id}" onclick="toggleComments(${topic.id})">Comentários</a>` : ''}

            <!-- Área de Respostas -->
            <div id="replyBox-${topic.id}" style="display: none; margin-top: 10px;">
                <textarea class="form-control mb-2" rows="3" placeholder="Digite sua resposta aqui..." id="replyContent-${topic.id}"></textarea>
                <button class="btn btn-success btn-sm" onclick="postReply(${topic.id}, null)">Enviar Resposta</button>
            </div>

            <div id="replies-${topic.id}" class="mt-3" style="display: none; border-left: 2px solid #ddd; padding-left: 10px;" data-collapsed="true"></div>
        </div>
    </div>
</div>`;





                    
                topicsContainer.insertAdjacentHTML("beforeend", topicHtml);

                // Carregar os dois primeiros comentários principais
                fetchReplies(topic.id, true, 2);
            });

            if (data.data.length < perPage) {
                window.removeEventListener("scroll", handleScroll);
            }

            page++;
        })
        .catch(error => {
            console.error("Erro ao carregar tópicos:", error);
            loading = false;
            loadingIndicator.style.display = "none";
        });
}



// Carregar respostas para um tópico ou comentário
function fetchReplies(topicId, isTopic = true, limit = null) {
    const apiUrl = isTopic
        ? `/api/topics/${topicId}/comments${limit ? `?limit=${limit}` : ''}`
        : `/api/comments/${topicId}/replies`;

    return fetch(apiUrl, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken // Inclui o CSRF Token no cabeçalho
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Erro ao carregar respostas.");
            }
            return response.json();
        })
        .then(data => {
            const repliesContainer = document.getElementById(`replies-${topicId}`);
            if (!repliesContainer) {
                console.error(`Contêiner de respostas não encontrado para ID: replies-${topicId}`);
                return;
            }

            repliesContainer.innerHTML = ''; // Limpa comentários anteriores
            data.forEach(reply => appendReply(topicId, reply, isTopic));
        })
        .catch(error =>
            console.error(`Erro ao carregar respostas para ${isTopic ? 'tópico' : 'comentário'} ${topicId}:`, error)
        );
}
// Adicionar uma resposta à lista de respostas
function appendReply(topicId, reply, isDirectReply = false) {
    const repliesContainerId = isDirectReply ? `replies-${topicId}` : `replies-${reply.parent_id}`;
    const repliesContainer = document.getElementById(repliesContainerId);

    if (!repliesContainer) {
        console.error(`Contêiner de respostas não encontrado para ID: ${repliesContainerId}`);
        return;
    }

    // Botão "Responder" aparece apenas se o usuário estiver autenticado
    const replyButtonHtml = window.isAuthenticated
        ? `<button class="btn btn-link btn-sm" onclick="toggleReplyBox(${reply.id})">Responder</button>`
        : '';

    // HTML da resposta
    const replyHtml = `
        <div id="reply-${reply.id}" class="reply mb-3">
            <div class="d-flex">
                <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${reply.user?.name || 'Desconhecido'}&size=s" 
                     alt="Avatar do usuário" class="rounded-circle" width="33" height="56">
                <div class="ms-2">
                    <h6 class="fw-bold text-uppercase">${reply.user?.name || 'Usuário Desconhecido'}</h6>
                    <p class="small mb-0">${reply.content}</p>
                    ${replyButtonHtml}
                </div>
            </div>

            <!-- Campo de resposta ao comentário -->
            <div id="replyBox-${reply.id}" style="display: none; margin-top: 10px;">
                <textarea class="form-control mb-2" rows="2" placeholder="Digite sua resposta aqui..." id="replyContent-${reply.id}"></textarea>
                <button class="btn btn-success btn-sm" onclick="postReply(${topicId}, ${reply.id})">Enviar Resposta</button>
            </div>

            <!-- Respostas encadeadas -->
            <div id="replies-${reply.id}" class="mt-3" style="border-left: 1px solid #ddd; padding-left: 10px;"></div>
        </div>
    `;

    // Adiciona a resposta ao contêiner
    repliesContainer.insertAdjacentHTML('beforeend', replyHtml);

    // Renderizar respostas aninhadas
    if (reply.replies && reply.replies.length > 0) {
        reply.replies.forEach(nestedReply => appendReply(topicId, nestedReply, false));
    }
}



// Enviar resposta para um tópico ou comentário
function postReply(topicId, parentId = null) {
    const contentElementId = parentId ? `replyContent-${parentId}` : `replyContent-${topicId}`;
    const contentElement = document.getElementById(contentElementId);

    if (!contentElement) {
        console.error(`Elemento com ID ${contentElementId} não encontrado.`);
        return;
    }

    const content = contentElement.value.trim();
    if (!content) {
        alert("A resposta não pode estar vazia.");
        return;
    }

    const apiUrl = parentId
        ? `/api/replies/${parentId}/replies` // Ou `/api/replies/{id}/replies`
        : `/api/topics/${topicId}/replies`;

    fetch(apiUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ content }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro na requisição: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Resposta enviada com sucesso:", data);
            appendReply(parentId || topicId, data, !parentId);

            // Limpar campo de texto
            contentElement.value = "";

            // Exibir mensagem de sucesso com animação
            showSuccessMessage();
        })
        .catch(error => {
            console.error("Erro ao postar resposta:", error);
        });
}

function showSuccessMessage() {
    const successMessage = `
        <div class="alert-success-custom d-flex align-items-center p-3 position-relative" style="margin-bottom: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi0-ZOqHJwpE2QNLZfWi5jH7KqdztKjQic7_Q7EWtHIehNsABn2Uh884b5KUh_mBHbhDiiAxWh0X5odT64laka0O7r_LpjaYW4VAexdR3iwYTnfbVlf4tHr-zetwJBIp-5FemDQ-9TPe48r/s0/ui_navigator_icon_fav_on.png" 
                 alt="Ícone de Sucesso" style="width: 44px; height: 40px; margin-right: 10px;">
            <span>Resposta publicada com sucesso!</span>
            <div class="progress-bar" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 5px; background-color: #a8e4a0; animation: progress-bar-animation 1s linear;"></div>
        </div>
    `;

    const messageContainer = document.getElementById('message-container') || createMessageContainer();
    messageContainer.innerHTML = successMessage;

    // Remover mensagem e atualizar a página após 1 segundo
    setTimeout(() => {
        messageContainer.innerHTML = "";
        location.reload(); // Atualiza a página
    }, 1000); // Tempo reduzido para 1 segundo
}

function createMessageContainer() {
    const container = document.createElement('div');
    container.id = 'message-container';
    container.style.position = 'fixed';
    container.style.top = '20px';
    container.style.right = '20px';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Adicione o estilo da animação ao documento
const style = document.createElement('style');
style.innerHTML = `
    @keyframes progress-bar-animation {
        from {
            width: 100%;
        }
        to {
            width: 0;
        }
    }
`;
document.head.appendChild(style);


// Alternar exibição do campo de resposta
function toggleReplyBox(parentId) {
    const replyBox = document.getElementById(`replyBox-${parentId}`);
    replyBox.style.display = replyBox.style.display === "none" || replyBox.style.display === "" ? "block" : "none";
}

// Carregar tópicos quando rolar para o final da página
window.addEventListener("scroll", () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100 && !loading) {
        fetchTopics();
    }
});

function toggleComments(topicId) {
    const commentsContainer = document.getElementById(`replies-${topicId}`);
    const toggleButton = document.getElementById(`toggle-comments-${topicId}`);
    const isCollapsed = commentsContainer.dataset.collapsed === 'true';

    if (isCollapsed) {
        // Mostrar comentários
        fetch(`/api/topics/${topicId}/comments`)
            .then(response => response.json())
            .then(data => {
                commentsContainer.innerHTML = ''; // Limpar comentários existentes
                data.forEach(comment => appendReply(topicId, comment, true));
                toggleButton.textContent = 'Ocultar Comentários';
                commentsContainer.style.display = 'block'; // Exibir comentários
                commentsContainer.dataset.collapsed = 'false';
            })
            .catch(error => {
                console.error(`Erro ao carregar comentários para o tópico ${topicId}:`, error);
            });
    } else {
        // Ocultar comentários
        commentsContainer.style.display = 'none'; // Ocultar comentários
        toggleButton.textContent = 'Visualizar Comentários';
        commentsContainer.dataset.collapsed = 'true';
    }
}

function renderTopic(topic) {
    const topicHtml = `
    <div class="topic-card card mb-3 p-3" id="topic-${topic.id}">
        <div class="d-flex">
            <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${topic.user.name}&size=l" alt="Avatar do usuário" class="rounded-circle" width="70" height="120">
            <div class="ms-3" style="max-width: 100%;">
                <h6 class="fw-bold text-uppercase">${topic.user.name}</h6>
                <p class="card-text" style="word-break: break-word;">${topic.content}</p>
                <p class="text-muted small">Publicado em ${new Date(topic.created_at).toLocaleString("pt-BR")}</p>
                <div class="d-flex mt-2 text-muted small">
                    <span id="comments-count-${topic.id}">${topic.total_comments_with_replies || 0} Comentários</span>
                </div>
                
                <!-- Botão de Responder -->
                ${topic.reply_button}

                <!-- Botão de Visualizar Comentários (se houver comentários) -->
                ${topic.total_comments_with_replies > 0 ? `
                <a href="javascript:void(0);" class="toggle-comments-link" id="toggle-comments-${topic.id}" onclick="toggleComments(${topic.id})">Comentários</a>` : ''}
                
                <!-- Área de Respostas -->
                <div id="replyBox-${topic.id}" style="display: none; margin-top: 10px;">
                    <textarea class="form-control mb-2" rows="3" placeholder="Digite sua resposta aqui..." id="replyContent-${topic.id}"></textarea>
                    <button class="btn btn-success btn-sm" onclick="postReply(${topic.id}, null)">Enviar Resposta</button>
                </div>
                
                <div id="replies-${topic.id}" class="mt-3" style="display: none; border-left: 2px solid #ddd; padding-left: 10px;" data-collapsed="true"></div>
            </div>
        </div>
    </div>`;
    document.getElementById("topicsContainer").insertAdjacentHTML("beforeend", topicHtml);
}

function renderReply(reply) {
    const replyHtml = `
    <div id="reply-${reply.id}" class="reply mb-3">
        <div class="d-flex">
            <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${reply.user.name}&size=s" alt="Avatar do usuário" class="rounded-circle" width="50" height="50">
            <div class="ms-2">
                <h6 class="fw-bold text-uppercase">${reply.user.name}</h6>
                <p class="small mb-0">${reply.content}</p>
                
                <!-- Botão de Responder -->
                ${reply.reply_button || ''}
            </div>
        </div>
        
        <!-- Respostas encadeadas -->
        <div id="replies-${reply.id}" class="mt-3" style="border-left: 1px solid #ddd; padding-left: 10px;"></div>
    </div>`;
    return replyHtml;
}



// Inicializar o carregamento de tópicos
fetchTopics();


fetchReplies(topic.id, true, 2);

