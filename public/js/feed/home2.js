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

function toggleComments(topicId) {
    const moreComments = document.getElementById(`moreComments-${topicId}`);
    const toggleButton = document.querySelector(`#topic-${topicId} .toggle-comments-btn`);

    if (moreComments.style.display === 'none' || moreComments.style.display === '') {
        moreComments.style.display = 'block';
        toggleButton.textContent = 'Ver menos';
    } else {
        moreComments.style.display = 'none';
        toggleButton.textContent = 'Ver mais';
    }
}

function toggleReplyBox(replyId) {
    const replyBox = document.getElementById(`replyBox-${replyId}`);
    replyBox.style.display = replyBox.style.display === 'none' ? 'block' : 'none';
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
// Oculta a caixa de texto, botão de publicar, botão de promover quarto, contador de caracteres e imagem do usuário
document.querySelector('textarea[name="content"]').style.display = 'none';
document.getElementById('publishButton').style.display = 'none';
document.querySelector('button[onclick="togglePromoteRoomForm()"]').style.display = 'none';
document.getElementById('charCount').style.display = 'none';
document.getElementById('userAvatar').style.display = 'none'; // Oculta a imagem do usuário

// Exibe o formulário de promoção do quarto
document.getElementById('promoteRoomForm').style.display = 'block';
}

function fetchRoomData() {
const roomId = document.getElementById('roomId').value;
if (!roomId) {
    alert('Por favor, insira o ID do quarto.');
    return;
}

fetch(`https://www.habbo.com.br/api/public/rooms/${roomId}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('roomName').textContent = data.name;
        document.getElementById('roomDescription').textContent = data.description;
        document.getElementById('roomCreationTime').textContent = data.creationTime;
        document.getElementById('roomTags').textContent = data.tags.join(', ');
        document.getElementById('roomMaxVisitors').textContent = data.maximumVisitors;
        document.getElementById('roomOwner').textContent = data.ownerName;
        document.getElementById('roomThumbnail').src = data.thumbnailUrl;

        document.getElementById('roomPreview').style.display = 'block';
    })
    .catch(error => {
        console.error('Erro ao buscar dados do quarto:', error);
        alert('Erro ao buscar dados do quarto. Verifique o ID e tente novamente.');
    });
}

function confirmRoomPromotion() {
const name = document.getElementById('roomName').textContent;
const description = document.getElementById('roomDescription').textContent;
const creationTime = document.getElementById('roomCreationTime').textContent;
const tags = document.getElementById('roomTags').textContent;
const maxVisitors = document.getElementById('roomMaxVisitors').textContent;
const ownerName = document.getElementById('roomOwner').textContent;
const thumbnailUrl = document.getElementById('roomThumbnail').src;
const roomId = document.getElementById('roomId').value;
const roomLink = `https://www.habbo.com.br/room/${roomId}`;

const content = `
<div style="display: flex; align-items: flex-start;">
    <img src="${thumbnailUrl}" alt="Imagem do Quarto" style="width:150px;height:150px; margin-right: 15px;">
    <div>
        <strong>Divulgação do Quarto do ${ownerName}</strong><br>
        <strong>Nome:</strong> ${name}<br>
        <strong>Descrição:</strong> ${description}<br>
        <strong>Dono do Quarto:</strong> ${ownerName}<br>
        <strong>Máximo de Visitantes:</strong> ${maxVisitors}<br>
        <strong>Tags:</strong> ${tags}<br>
        <strong>Data de Criação:</strong> ${creationTime}<br><br>
        
        <a href="${roomLink}" target="_blank" style="display: inline-flex; align-items: center; background-color: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px;">
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhd6VL8yIeCFQlqWGxm00_M2FQuHn8S-4XThv4oKXsPj5DyKy9k5nbShSYJ8RYPiiBoyIzWjhAHH50_v_QhshM7ZQJPrkyfXX46l77rEGh50uMZ89xuLdO8i2KswIVSZhiBFj7W8V_O8_0/s0/icon_add_to_trade.png" alt="Ícone" style="width: 20px; height: 20px; margin-right: 8px;">
            Ir para o quarto
        </a>
    </div>
</div>`;

document.querySelector('textarea[name="content"]').value = content;

// Submete o formulário automaticamente após adicionar o conteúdo
document.getElementById('createTopicForm').submit();
}

// Função para cancelar a promoção e restaurar a visualização inicial
function cancelPromotion() {
// Restaura a exibição dos elementos ocultos
document.querySelector('textarea[name="content"]').style.display = 'block';
document.getElementById('publishButton').style.display = 'inline-block';
document.querySelector('button[onclick="togglePromoteRoomForm()"]').style.display = 'inline-block';
document.getElementById('charCount').style.display = 'block';
document.getElementById('userAvatar').style.display = 'block'; // Mostra a imagem do usuário

// Oculta o formulário de promoção e pré-visualização do quarto
document.getElementById('promoteRoomForm').style.display = 'none';
document.getElementById('roomPreview').style.display = 'none';
}

function toggleReportHabboForm() {
// Oculta o botão de criar tópico e o formulário de "Denunciar Habbo"
document.querySelector('textarea[name="content"]').style.display = 'none';
document.getElementById('publishButton').style.display = 'none';
document.querySelector('button[onclick="toggleReportHabboForm()"]').style.display = 'none';

// Exibe o formulário de denúncia
document.getElementById('reportHabboForm').style.display = 'block';
}

function fetchHabboData() {
const nickname = document.getElementById('habboNickname').value;
if (!nickname) {
    alert('Por favor, insira o nickname do Habbo.');
    return;
}

fetch(`https://www.habbo.com.br/api/public/users?name=${nickname}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('reportName').textContent = data.name;
        document.getElementById('reportMotto').textContent = data.motto;
        document.getElementById('reportOnlineStatus').textContent = data.online ? 'Sim' : 'Não';
        document.getElementById('reportLastAccess').textContent = data.lastAccessTime || 'Indisponível';
        document.getElementById('reportMemberSince').textContent = data.memberSince || 'Indisponível';

        // Oculta o formulário de busca e exibe a prévia da denúncia
        document.getElementById('reportHabboForm').style.display = 'none';
        document.getElementById('reportPreview').style.display = 'block';
    })
    .catch(error => {
        console.error('Erro ao buscar dados do Habbo:', error);
        alert('Erro ao buscar dados do Habbo. Verifique o nickname e tente novamente.');
    });
}

function confirmReport() {
const name = document.getElementById('reportName').textContent;
const motto = document.getElementById('reportMotto').textContent;
const onlineStatus = document.getElementById('reportOnlineStatus').textContent;
const lastAccess = document.getElementById('reportLastAccess').textContent;
const memberSince = document.getElementById('reportMemberSince').textContent;
const reason = document.getElementById('reportReason').value;

const contentTextarea = document.querySelector('textarea[name="content"]');
contentTextarea.value = `
<strong>Denúncia de Habbo</strong><br>
<strong>Nome:</strong> ${name}<br>
<strong>Missão:</strong> ${motto}<br>
<strong>Online:</strong> ${onlineStatus}<br>
<strong>Último Acesso:</strong> ${lastAccess}<br>
<strong>Membro Desde:</strong> ${memberSince}<br>
<strong>Motivo da Denúncia:</strong> ${reason}<br>
`;

// Submete o formulário automaticamente após adicionar o conteúdo
document.getElementById('createTopicForm').submit();
}

function cancelReport() {
// Restaura a exibição dos elementos originais
document.querySelector('textarea[name="content"]').style.display = 'block';
document.getElementById('publishButton').style.display = 'inline-block';
document.querySelector('button[onclick="toggleReportHabboForm()"]').style.display = 'inline-block';

// Oculta o formulário de denúncia e prévia
document.getElementById('reportHabboForm').style.display = 'none';
document.getElementById('reportPreview').style.display = 'none';
}


let page = 1;
const perPage = 7;
let loading = false;
const topicsContainer = document.getElementById("topicsContainer");
const loadingIndicator = document.getElementById("loadingIndicator");

// Armazena todos os comentários carregados para cada tópico
let allComments = {};







function fetchTopics() {
if (loading) return;
loading = true;
loadingIndicator.style.display = "block";

fetch(`/api/feed?page=${page}&perPage=${perPage}`)
    .then((response) => response.json())
    .then((data) => {
        loadingIndicator.style.display = "none";
        loading = false;

        data.data.forEach((topic) => {
            allComments[topic.id] = topic.comments; // Salva todos os comentários e respostas

            const totalComments = topic.comments.reduce(
                (acc, comment) => acc + 1 + (comment.replies?.length || 0),
                0
            );

            const topicHtml = `
                <div class="topic-card card mb-3 p-3" id="topic-${topic.id}">
                    <div class="d-flex">
                        <!-- Avatar do usuário -->
                        <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=${topic.user.name}&size=l" alt="Avatar do usuário" class="rounded-circle" width="70" height="120">
                        <div class="ms-3" style="max-width: 100%;">

                            <!-- Detalhes do Tópico -->
                            <h6 class="fw-bold text-uppercase">${topic.user.name}</h6>
                            <p class="card-text" style="word-break: break-word;">${topic.content}</p>
                            <p class="text-muted small">Publicado em ${new Date(topic.created_at).toLocaleString("pt-BR")}</p>

                            <!-- Informações adicionais -->
                            <div class="d-flex mt-2 text-muted small">
                                <span class="me-3"><i class="far fa-comments"></i> ${totalComments} Comentários</span>
                                <span class="me-3"><i class="far fa-eye"></i> ${topic.views} Visualizações</span>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="d-flex mt-3">
                                ${
                                    parseInt(document.body.dataset.userId) === topic.user_id
                                        ? `
                                            <button type="button" id="deleteButton-${topic.id}" 
                                                class="btn btn-danger btn-sm" 
                                                onclick="showDeleteConfirmation(${topic.id})">
                                                Apagar
                                            </button>
                                            <div id="deleteConfirm-${topic.id}" class="delete-confirm-box mt-2" style="display: none;">
                                                <span class="text-muted small">Tem certeza que deseja apagar este tópico?</span>
                                                <div class="d-inline-flex gap-2">
                                                    <button class="btn btn-danger btn-sm" onclick="deleteTopic(${topic.id})">Confirmar</button>
                                                    <button class="btn btn-outline-secondary btn-sm" onclick="cancelDelete(${topic.id})">Cancelar</button>
                                                </div>
                                            </div>
                                          `
                                        : ""
                                }
                                <button type="button" class="btn btn-outline-primary btn-sm ms-2" id="replyButton-${topic.id}" onclick="toggleReplyBox('topic-${topic.id}')">Responder</button>
                            </div>

                            <!-- Caixa de resposta -->
                            <div id="replyBox-topic-${topic.id}" class="mt-2" style="display: none;">
                                <form method="POST" action="/topics/${topic.id}/comments">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <textarea name="content" class="form-control" rows="2" maxlength="300" required placeholder="Digite seu comentário" style="width: 100%;"></textarea>
                                    <button type="submit" class="btn btn-outline-primary mt-2">Comentar</button>
                                </form>
                            </div>

                            <!-- Comentários -->
                            <div class="comments-section mt-3">
                                <h6>Comentários</h6>
                                <div id="comments-container-${topic.id}">
                                    ${renderComments(topic.comments.slice(0, 1))} <!-- Exibe apenas o primeiro comentário inicialmente -->
                                </div>
                                ${
                                    topic.comments.length > 1
                                        ? `
                                            <button class="btn btn-link text-decoration-none toggle-comments-btn mt-2" onclick="toggleComments(${topic.id})">
                                                Ver Mais
                                            </button>
                                          `
                                        : ""
                                }
                            </div>
                        </div>
                    </div>
                </div>`;
            topicsContainer.insertAdjacentHTML("beforeend", topicHtml);
        });

        if (data.data.length < perPage) {
            window.removeEventListener("scroll", handleScroll);
        }

        page++;
    })
    .catch((error) => {
        console.error("Erro ao carregar tópicos:", error);
        loading = false;
        loadingIndicator.style.display = "none";
    });
}









function submitReply(parentId) {
const replyText = document.getElementById(`replyText-${parentId}`).value;

fetch(`/topics/${topicId}/comments`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ content: replyText, parent_id: parentId })
})
.then(response => response.json())
.then(data => {
    if (data.message) {
        // Atualiza os comentários dinamicamente
        fetchComments(topicId);
    }
})
.catch(error => console.error('Erro ao enviar resposta:', error));
}


function renderComments(comments) {
return comments
    .map(comment => {
        // Renderiza as respostas associadas ao comentário
        const repliesHtml = comment.replies
            .map(reply => `
                <div class="reply-box" id="comment-${reply.id}" style="margin-left: 20px;">
                    <strong>${reply.user.name}</strong>: ${reply.content}
                    <p class="text-muted small">${new Date(reply.created_at).toLocaleString()}</p>
                </div>
            `)
            .join('');

        return `
            <div class="comment-box" id="comment-${comment.id}">
                <strong>${comment.user.name}</strong>: ${comment.content}
                <p class="text-muted small">${new Date(comment.created_at).toLocaleString()}</p>
                <button onclick="showReplyBox(${comment.id})">Responder</button>
                <div id="replyBox-${comment.id}" style="display: none;">
                    <textarea id="replyText-${comment.id}" placeholder="Digite sua resposta"></textarea>
                    <button onclick="submitReply(${comment.id})">Enviar</button>
                </div>
                ${repliesHtml} <!-- Insere as respostas -->
            </div>
        `;
    })
    .join('');
}









function toggleComments(topicId) {
const commentsContainer = document.getElementById(`comments-container-${topicId}`);
const button = commentsContainer.nextElementSibling;

if (button.innerText === "Ver Mais") {
    commentsContainer.innerHTML = renderComments(allComments[topicId]); // Exibe todos os comentários
    button.innerText = "Ver Menos";
} else {
    commentsContainer.innerHTML = renderComments(allComments[topicId].slice(0, 1)); // Exibe apenas o primeiro comentário
    button.innerText = "Ver Mais";
}
}



function toggleReplyBox(replyId) {
console.log(`Procurando elemento com ID: replyBox-${replyId}`);
const replyBox = document.getElementById(`replyBox-${replyId}`);

if (!replyBox) {
    console.error(`Elemento replyBox-${replyId} não encontrado no DOM.`);
    console.log("IDs disponíveis no DOM:");
    document.querySelectorAll('[id^="replyBox-"]').forEach(el => console.log(el.id));
    return; // Pare a execução para evitar erros
}

replyBox.style.display = replyBox.style.display === "none" ? "block" : "none";
}


function showDeleteConfirmation(topicId) {
document.getElementById(`deleteButton-${topicId}`).style.display = "none";
document.getElementById(`deleteConfirm-${topicId}`).style.display = "block";
}

function cancelDelete(topicId) {
document.getElementById(`deleteConfirm-${topicId}`).style.display = "none";
document.getElementById(`deleteButton-${topicId}`).style.display = "inline-block";
}


function deleteTopic(topicId) {
fetch(`/topics/${topicId}`, {
    method: "DELETE",
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
})
    .then(response => {
        if (response.ok) {
            document.getElementById(`topic-${topicId}`).remove();
        } else {
            alert("Erro ao apagar o tópico.");
        }
    })
    .catch(error => console.error("Erro ao apagar o tópico:", error));
}

window.addEventListener("scroll", () => {
if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100 && !loading) {
    fetchTopics();
}
});

fetchTopics();


