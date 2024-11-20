// Exibe e oculta o campo de resposta
function toggleReplyBox(topicId) {
    const replyBox = document.getElementById(`replyBox-${topicId}`);
    if (replyBox) {
        replyBox.style.display = replyBox.style.display === 'none' || replyBox.style.display === '' ? 'block' : 'none';
    } else {
        console.error(`Campo de resposta não encontrado para o tópico/comentário ID: ${topicId}`);
    }
}

// Recupera o token CSRF do meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (!csrfToken) {
    console.error("Token CSRF não encontrado no meta tag.");
}

// Função para postar uma resposta
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
        ? `/api/replies/${parentId}/replies`
        : `/api/topics/${topicId}/replies`;

    // Mostrar animação de carregamento
    showLoading();

    fetch(apiUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ content }),
    })
        .then((response) => {
            if (!response.ok) {
                return response.json().then((errData) => {
                    throw new Error(errData.message || "Erro ao postar resposta.");
                });
            }
            return response.json();
        })
        .then((data) => {
            // Ocultar animação de carregamento
            hideLoading();

            // Exibir alerta de sucesso
            showSuccessAlert("Resposta adicionada com sucesso!");

            // Recarregar a página para atualizar os comentários
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        })
        .catch((error) => {
            // Ocultar animação de carregamento
            hideLoading();
            console.error("Erro ao postar resposta:", error);
            alert(error.message || "Não foi possível postar a resposta. Tente novamente mais tarde.");
        });
}


// Adiciona a resposta à lista de respostas
function appendReply(topicId, reply, isDirectReply = false) {
    const repliesContainerId = isDirectReply ? `replies-${topicId}` : `replies-${reply.parent_id}`;
    const repliesContainer = document.getElementById(repliesContainerId);

    if (!repliesContainer) {
        console.error(`Contêiner de respostas não encontrado para ID: ${repliesContainerId}`);
        return;
    }

    // HTML da resposta
    const replyHtml = `
        <div id="reply-${reply.id}" class="reply mb-3">
            <div class="d-flex">
                <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${reply.user?.name || 'Desconhecido'}&size=s" 
                     alt="Avatar do usuário" class="rounded-circle" width="50" height="50">
                <div class="ms-2">
                    <h6 class="fw-bold text-uppercase">${reply.user?.name || 'Usuário Desconhecido'}</h6>
                    <p class="small mb-0">${reply.content}</p>
                    <!-- Botão de responder apenas para usuários logados -->
                    ${window.isAuthenticated ? `
                        <button class="btn btn-link btn-sm" onclick="toggleReplyBox(${reply.id})">Responder</button>
                    ` : ''}
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
        reply.replies.forEach((nestedReply) => appendReply(topicId, nestedReply, false));
    }





// Carrega respostas de um tópico
function fetchReplies(topicId) {
    fetch(`/api/topics/${topicId}/replies`)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Erro ao carregar respostas.");
            }
            return response.json();
        })
        .then((data) => {
            const repliesContainer = document.getElementById(`replies-${topicId}`);
            if (repliesContainer) {
                data.forEach((reply) => appendReply(topicId, reply, true));
            } else {
                console.error(`Contêiner de respostas não encontrado para tópico ID: ${topicId}`);
            }
        })
        .catch((error) => {
            console.error('Erro ao carregar respostas:', error);
        });
}


// Função para exibir o alerta de sucesso
function showSuccessAlert(message) {
    const alertContainer = document.createElement("div");
    alertContainer.className = "alert-success-custom d-flex align-items-center p-3";
    alertContainer.innerHTML = `
        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi0-ZOqHJwpE2QNLZfWi5jH7KqdztKjQic7_Q7EWtHIehNsABn2Uh884b5KUh_mBHbhDiiAxWh0X5odT64laka0O7r_LpjaYW4VAexdR3iwYTnfbVlf4tHr-zetwJBIp-5FemDQ-9TPe48r/s0/ui_navigator_icon_fav_on.png" 
             alt="Ícone de Sucesso" style="width: 44px; height: 40px; margin-right: 10px;">
        <span>${message}</span>
    `;
    document.body.prepend(alertContainer);

    setTimeout(() => {
        alertContainer.remove();
    }, 3000);
}

// Função para exibir a animação de carregamento


// Função para esconder a animação de carregamento
function hideLoading() {
    const loadingOverlay = document.getElementById("loadingOverlay");
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

function toggleComments(topicId) {
    const commentsContainer = document.getElementById(`comments-${topicId}`);
    const toggleButton = document.getElementById(`toggle-comments-${topicId}`);

    if (!commentsContainer || !toggleButton) {
        console.error(`Elementos não encontrados para o tópico ID: ${topicId}`);
        return;
    }

    const isCollapsed = commentsContainer.dataset.collapsed === 'true';

    if (isCollapsed) {
        // Buscar TODOS os comentários
        fetch(`/api/topics/${topicId}/comments`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar comentários.');
                }
                return response.json();
            })
            .then(data => {
                commentsContainer.innerHTML = ''; // Limpa os anteriores
                data.forEach(comment => appendComment(commentsContainer, comment));
                toggleButton.textContent = 'Ocultar Comentários';
                commentsContainer.dataset.collapsed = 'false';
                commentsContainer.style.display = 'block'; // Certifique-se de que os comentários sejam exibidos
            })
            .catch(error => console.error('Erro ao buscar comentários:', error));
    } else {
        // Esconde os comentários
        commentsContainer.innerHTML = '';
        toggleButton.textContent = 'Visualizar Comentários';
        commentsContainer.dataset.collapsed = 'true';
        commentsContainer.style.display = 'none';
    }
}







function appendComment(container, comment) {
    const commentHtml = `
        <div class="comment mb-3">
            <strong>${comment.user.name}</strong>: ${comment.content}
            ${window.isAuthenticated ? `
                <button class="btn btn-link btn-sm" onclick="toggleReplyBox(${comment.id})">Responder</button>
            ` : ''}
            <div id="replyBox-${comment.id}" style="display: none; margin-top: 10px;">
                <textarea class="form-control mb-2" rows="2" placeholder="Digite sua resposta aqui..." id="replyContent-${comment.id}"></textarea>
                <button class="btn btn-success btn-sm" onclick="postReply(${comment.topic_id}, ${comment.id})">Enviar Resposta</button>
            </div>
            ${comment.replies ? `
                <div id="replies-${comment.id}" class="mt-3" style="border-left: 1px solid #ddd; padding-left: 10px;">
                    ${comment.replies.map(reply => `
                        <div class="reply">
                            <strong>${reply.user.name}</strong>: ${reply.content}
                        </div>
                    `).join('')}
                </div>
            ` : ''}
        </div>
    `;
    container.insertAdjacentHTML('beforeend', commentHtml);
}
}

function showLoading() {
    const loadingOverlay = document.createElement("div");
    loadingOverlay.id = "loadingOverlay";
    loadingOverlay.style.position = "fixed";
    loadingOverlay.style.top = 0;
    loadingOverlay.style.left = 0;
    loadingOverlay.style.width = "100%";
    loadingOverlay.style.height = "100%";
    loadingOverlay.style.background = "rgba(255, 255, 255, 0.7)";
    loadingOverlay.style.zIndex = 9999;
    loadingOverlay.style.display = "flex";
    loadingOverlay.style.justifyContent = "center";
    loadingOverlay.style.alignItems = "center";
    loadingOverlay.innerHTML = `
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Carregando...</span>
        </div>
    `;
    document.body.appendChild(loadingOverlay);
}

function showSuccessAlert(message) {
    const alertContainer = document.createElement("div");
    alertContainer.className = "alert-success-custom d-flex align-items-center p-3";
    alertContainer.innerHTML = `
        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi0-ZOqHJwpE2QNLZfWi5jH7KqdztKjQic7_Q7EWtHIehNsABn2Uh884b5KUh_mBHbhDiiAxWh0X5odT64laka0O7r_LpjaYW4VAexdR3iwYTnfbVlf4tHr-zetwJBIp-5FemDQ-9TPe48r/s0/ui_navigator_icon_fav_on.png" 
             alt="Ícone de Sucesso" style="width: 44px; height: 40px; margin-right: 10px;">
        <span>${message}</span>
    `;
    document.body.prepend(alertContainer);

    setTimeout(() => {
        alertContainer.remove();
    }, 3000);
}