function togglePromoteRoomForm() {
    // Verificar se os elementos existem antes de manipulá-los
    const reportButton = document.querySelector('button[onclick="toggleReportHabboForm()"]');
    const promoteForm = document.getElementById('promoteRoomForm');
    const promoteButton = document.querySelector('button[onclick="togglePromoteRoomForm()"]');
    const contentTextarea = document.querySelector('textarea[name="content"]');
    const publishButton = document.getElementById('publishButton');
    const charCount = document.getElementById('charCount');
    const userAvatar = document.getElementById('userAvatar');

    if (reportButton) reportButton.style.display = 'none'; // Ocultar botão "Denunciar Habbo"
    if (promoteForm) promoteForm.style.display = 'block'; // Exibir o formulário "Divulgar Quarto"
    if (promoteButton) promoteButton.style.display = 'none'; // Ocultar botão "Divulgar Quarto"
    if (contentTextarea) contentTextarea.style.display = 'none'; // Ocultar textarea de pensamento
    if (publishButton) publishButton.style.display = 'none'; // Ocultar botão "Postar"
    if (charCount) charCount.style.display = 'none'; // Ocultar contador de caracteres
    if (userAvatar) userAvatar.style.display = 'none'; // Ocultar avatar de pensamento
}

function cancelPromotion() {
    // Verificar se os elementos existem antes de manipulá-los
    const reportButton = document.querySelector('button[onclick="toggleReportHabboForm()"]');
    const promoteForm = document.getElementById('promoteRoomForm');
    const promoteButton = document.querySelector('button[onclick="togglePromoteRoomForm()"]');
    const contentTextarea = document.querySelector('textarea[name="content"]');
    const publishButton = document.getElementById('publishButton');
    const charCount = document.getElementById('charCount');
    const userAvatar = document.getElementById('userAvatar');

    if (reportButton) reportButton.style.display = 'inline-block'; // Restaurar botão "Denunciar Habbo"
    if (promoteButton) promoteButton.style.display = 'inline-block'; // Restaurar botão "Divulgar Quarto"
    if (contentTextarea) contentTextarea.style.display = 'block'; // Restaurar textarea de pensamento
    if (publishButton) publishButton.style.display = 'inline-block'; // Restaurar botão "Postar"
    if (charCount) charCount.style.display = 'block'; // Restaurar contador de caracteres
    if (userAvatar) userAvatar.style.display = 'block'; // Restaurar avatar de pensamento

    if (promoteForm) promoteForm.style.display = 'none'; // Ocultar o formulário "Divulgar Quarto"
}




function fetchRoomData() {
    const roomIdInput = document.getElementById('roomId');
    const roomId = roomIdInput ? roomIdInput.value.trim() : null;

    if (!roomId) {
        alert('Por favor, insira o ID do quarto.');
        return;
    }

    // URL da API do Habbo
    const apiUrl = `https://www.habbo.com.br/api/public/rooms/${roomId}`;

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Quarto não encontrado ou API indisponível.');
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados do quarto recebidos:', data);

            // Exibir os dados na prévia
            document.getElementById('roomName').textContent = data.name || 'N/A';
            document.getElementById('roomDescription').textContent = data.description || 'N/A';
            // Formatar a data para o formato brasileiro com dia da semana capitalizado
            const creationDate = new Date(data.creationTime);
            const dayOfWeek = creationDate.toLocaleDateString('pt-BR', { weekday: 'long' });
            const capitalizedDayOfWeek = dayOfWeek.charAt(0).toUpperCase() + dayOfWeek.slice(1);

            const formattedDate = creationDate.toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
            });
            const formattedTime = creationDate.toLocaleTimeString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit',
            });

            document.getElementById('roomCreationTime').textContent = `${capitalizedDayOfWeek}, ${formattedDate}, às ${formattedTime}`;
            document.getElementById('roomOwner').textContent = data.ownerName || 'N/A';
            document.getElementById('roomOwner').textContent = data.ownerName || 'N/A';
            const groupElement = document.getElementById('roomGroup');
            if (groupElement) {
                groupElement.textContent = data.habboGroupId
                    ? `Grupo: ${data.habboGroupId}`
                    : 'Sem grupo associado.';
            }

            // Exibir a imagem do quarto
            const roomThumbnail = document.getElementById('roomThumbnail');
            if (roomThumbnail) {
                roomThumbnail.src = data.thumbnailUrl || 'https://via.placeholder.com/150'; // URL padrão caso não haja imagem
            }

            // Exibir o contêiner da prévia
            const roomDetails = document.getElementById('roomDetails');
            if (roomDetails) {
                roomDetails.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erro ao buscar dados do quarto:', error);
            alert('Erro ao buscar os dados do quarto. Verifique o ID e tente novamente.');
        });
}


function confirmRoomPromotion() {
    const roomName = document.getElementById('roomName').textContent || 'N/A';
    const roomDescription = document.getElementById('roomDescription').textContent || 'N/A';
    const roomCreationTime = document.getElementById('roomCreationTime').textContent || 'N/A';
    const roomOwner = document.getElementById('roomOwner').textContent || 'N/A';

    // Verifica se a imagem do quarto existe
    const roomThumbnailElement = document.getElementById('roomThumbnail');
    const roomThumbnail = roomThumbnailElement && roomThumbnailElement.src
        ? roomThumbnailElement.src
        : 'https://via.placeholder.com/150'; // Imagem padrão

    // Conteúdo formatado para o input de pensamento
    const content = `
        <div style="display: flex; align-items: flex-start;">
            <img src="${roomThumbnail}" alt="Imagem do Quarto" style="width: 150px; height: 150px; margin-right: 15px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">
            <div>
                <strong>Divulgação do Quarto</strong><br>
                <strong>Nome:</strong> ${roomName}<br>
                <strong>Descrição:</strong> ${roomDescription}<br>
                <strong>Data de Criação:</strong> ${roomCreationTime}<br>
                <strong>Proprietário:</strong> ${roomOwner}<br>
            </div>
        </div>
    `;

    // Inserir o conteúdo no textarea de pensamento
    const contentTextarea = document.querySelector('textarea[name="content"]');
    if (contentTextarea) {
        contentTextarea.value = content;
    }

    // Submeter o formulário
    const topicForm = document.getElementById('createTopicForm');
    if (topicForm) {
        topicForm.submit();
    }
}
