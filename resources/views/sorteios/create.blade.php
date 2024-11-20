<div class="container mt-4">
    <div class="custom-card p-4 shadow-sm">
    <h2>Criar Sorteio</h2>

    <form action="{{ route('sorteios.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Título do Sorteio</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrição do Sorteio</label>
            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
            <small class="text-muted">Informe na descrição quando será realizado o sorteio.</small>
        </div>

        <!-- Adicionar Ganhadores e Prêmios -->
        <div class="mb-3">
            
            <button type="button" class="btn btn-secondary mb-2" onclick="toggleAddGanhadorForm()">Adicionar Prêmio</button>
            
            <!-- Formulário para adicionar um ganhador -->
            <div id="addGanhadorForm" style="display: none;">
                <div class="mb-2">
                    <label class="form-label">Tipo de Prêmio</label>
                    <select id="premio_tipo" class="form-select">
                        <option value="cambios">Câmbios</option>
                        <option value="hc">HC</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label for="premio_quantidade" class="form-label">Quantidade do Prêmio</label>
                    <input type="number" id="premio_quantidade" class="form-control">
                </div>

                <button type="button" class="btn btn-primary" onclick="adicionarGanhador()">Adicionar</button>
            </div>

            <!-- Lista de ganhadores adicionados -->
            <ul id="listaGanhadores" class="list-group mt-3"></ul>
        </div>

        <!-- Campos ocultos para armazenar os ganhadores e prêmios -->
        <div id="ganhadoresContainer"></div>
        <input type="hidden" name="quantidade_ganhadores" id="quantidade_ganhadores">

        <button type="submit" class="btn btn-success">Postar Sorteio</button></br></br></br>
    </form>
</div>
</div>


<style>
.custom-card {
    background-color: #ffffff;
    border: none;
    border-radius: 5px;
    box-shadow: none; /* Remova caso queira sombra */
}

.form-control {
    background-color: #f6f7f6; /* Cor do fundo */
    border: none; /* Cor e espessura da borda */
    border-radius: 0px; /* Cantos arredondados (defina 0 para cantos quadrados) */
    padding: 10px; /* Espaçamento interno */
    transition: border-color 0.3s ease, background-color 0.3s ease; /* Transição suave para interações */
}

.form-control:focus {
    background-color: #ffeef6; /* Cor do fundo ao focar */
    border-color: #b30056; /* Cor da borda ao focar */
    outline: none; /* Remove o outline padrão */
}

</style>
