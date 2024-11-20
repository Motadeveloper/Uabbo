document.addEventListener('DOMContentLoaded', function () {
    // Função para exibir o formulário "Denunciar Habbo" e ocultar outros elementos
    window.toggleReportHabboForm = function () {
        const promoteButton = document.querySelector('button[onclick="togglePromoteRoomForm()"]');
        const reportForm = document.getElementById('reportHabboForm');
        const reportButton = document.querySelector('button[onclick="toggleReportHabboForm()"]');
        const contentTextarea = document.querySelector('textarea[name="content"]');
        const publishButton = document.getElementById('publishButton');
        const charCount = document.getElementById('charCount');
        const userAvatar = document.getElementById('userAvatar'); // Avatar do pensamento

        if (promoteButton) promoteButton.style.display = 'none'; // Ocultar botão "Divulgar Quarto"
        if (reportForm) reportForm.style.display = 'block'; // Exibir formulário "Denunciar Habbo"
        if (reportButton) reportButton.style.display = 'none'; // Ocultar botão "Denunciar Habbo"
        if (contentTextarea) contentTextarea.style.display = 'none'; // Ocultar textarea de pensamento
        if (publishButton) publishButton.style.display = 'none'; // Ocultar botão "Postar"
        if (charCount) charCount.style.display = 'none'; // Ocultar contador de caracteres
        if (userAvatar) userAvatar.style.display = 'none'; // Ocultar avatar do pensamento
    };

    // Função para cancelar o formulário "Denunciar Habbo" e restaurar outros elementos
    window.cancelReport = function () {
        const promoteButton = document.querySelector('button[onclick="togglePromoteRoomForm()"]');
        const reportForm = document.getElementById('reportHabboForm');
        const reportButton = document.querySelector('button[onclick="toggleReportHabboForm()"]');
        const contentTextarea = document.querySelector('textarea[name="content"]');
        const publishButton = document.getElementById('publishButton');
        const charCount = document.getElementById('charCount');
        const userAvatar = document.getElementById('userAvatar'); // Avatar do pensamento

        if (promoteButton) promoteButton.style.display = 'inline-block'; // Restaurar botão "Divulgar Quarto"
        if (reportForm) reportForm.style.display = 'none'; // Ocultar formulário "Denunciar Habbo"
        if (reportButton) reportButton.style.display = 'inline-block'; // Restaurar botão "Denunciar Habbo"
        if (contentTextarea) contentTextarea.style.display = 'block'; // Restaurar textarea de pensamento
        if (publishButton) publishButton.style.display = 'inline-block'; // Restaurar botão "Postar"
        if (charCount) charCount.style.display = 'block'; // Restaurar contador de caracteres
        if (userAvatar) userAvatar.style.display = 'block'; // Restaurar avatar do pensamento
    };

    // Atualizar avatar em tempo real ao digitar o nickname
    const habboNicknameInput = document.getElementById('habboNickname');
    const habboAvatarImage = document.getElementById('habboAvatarImage');

    if (habboNicknameInput && habboAvatarImage) {
        habboNicknameInput.addEventListener('input', function () {
            const nickname = this.value.trim();
            habboAvatarImage.src = nickname
                ? `https://www.habbo.com.br/habbo-imaging/avatarimage?user=${nickname}&action=std&direction=2&head_direction=3&gesture=sml&size=l`
                : 'https://www.habbo.com.br/habbo-imaging/avatarimage?user=default&action=std&direction=2&head_direction=3&gesture=sml&size=l';
        });
    }
});


