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

function fetchHabboData() {
    const habboNicknameInput = document.getElementById('habboNickname');
    const nickname = habboNicknameInput ? habboNicknameInput.value.trim() : null;

    if (!nickname) {
        alert('Por favor, insira o nickname do Habbo.');
        return;
    }

    const apiUrl = `https://www.habbo.com.br/api/public/users?name=${nickname}`;

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Habbo não encontrado ou API indisponível.');
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados do Habbo recebidos:', data);

            // Atualizar os elementos HTML com os dados recebidos
            document.getElementById('reportName').textContent = data.name || 'N/A';
            document.getElementById('reportMotto').textContent = data.motto || 'N/A';
            document.getElementById('reportOnlineStatus').textContent = data.online ? 'Sim' : 'Não';

            document.getElementById('reportLastAccess').textContent = data.lastAccessTime
                ? new Date(data.lastAccessTime).toLocaleString('pt-BR', {
                      day: '2-digit',
                      month: '2-digit',
                      year: 'numeric',
                      hour: '2-digit',
                      minute: '2-digit',
                  })
                : 'Indisponível';

            document.getElementById('reportMemberSince').textContent = data.memberSince
                ? new Date(data.memberSince).toLocaleString('pt-BR', {
                      day: '2-digit',
                      month: '2-digit',
                      year: 'numeric',
                  })
                : 'Indisponível';

            document.getElementById('habboAvatarImage').src = `https://www.habbo.com.br/habbo-imaging/avatarimage?user=${nickname}&action=std&direction=2&head_direction=3&gesture=sml&size=l`;

            // Exibir prévia e botão
            document.getElementById('reportPreview').style.display = 'block';
            document.getElementById('postReportButton').style.display = 'block'; // Exibir o botão de "Postar Denúncia"
        })
        .catch(error => {
            console.error('Erro ao buscar dados do Habbo:', error);
            alert('Erro ao buscar os dados do Habbo. Verifique o nickname e tente novamente.');
        });
}



function postHabboReport() {
    const name = document.getElementById('reportName').textContent;
    const motto = document.getElementById('reportMotto').textContent;
    const onlineStatus = document.getElementById('reportOnlineStatus').textContent;
    const lastAccess = document.getElementById('reportLastAccess').textContent;
    const memberSince = document.getElementById('reportMemberSince').textContent;
    const reason = document.getElementById('reportReason').value.trim();

    if (!reason) {
        alert('Por favor, preencha o motivo da denúncia.');
        return;
    }

    const content = `
        <strong>Denúncia de Habbo</strong><br>
        <strong>Nome:</strong> ${name}<br>
        <strong>Missão:</strong> ${motto}<br>
        <strong>Online:</strong> ${onlineStatus}<br>
        <strong>Último Acesso:</strong> ${lastAccess}<br>
        <strong>Membro Desde:</strong> ${memberSince}<br>
        <strong>Motivo da Denúncia:</strong> ${reason}<br>
    `;

    const contentTextarea = document.querySelector('textarea[name="content"]');
    contentTextarea.value = content;

    // Submete o formulário automaticamente após adicionar o conteúdo
    document.getElementById('createTopicForm').submit();
}
