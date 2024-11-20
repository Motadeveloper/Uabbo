@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/sorteio/index.css') }}">

<div class="container">
    <div class="header-container">
    <h2>Sorteios</h2>

  
        <!-- Botão para mostrar o formulário de criação de sorteio -->
        @if(auth()->check())
        <button id="showCreateForm" class="custom-button">
    <img src="https://www.habboassets.com/images/catalog-icons/214" alt="Ícone" class="button-icon">
    Cridar um Sorteio
</button>
    <button id="cancelCreateForm" class="btn btn-secondary mb-3" style="display: none;">Cancelar</button>
@else
<a href="{{ route('login') }}" class="custom-button">
    <img src="https://www.habboassets.com/images/catalog-icons/214" alt="Ícone" class="button-icon">
    Criar um Sorteio
</a>

@endif

</div>
        <!-- Contêiner para o formulário de criação, inicialmente oculto -->
        <div id="createFormContainer" style="display: none; margin-top: 20px;">
            <div id="createFormContent"></div>
        </div>
    



        



    <!-- Exibição de sorteios em andamento -->
    <h3 class="mt-5">Sorteios em Andamento</h3>
    <div id="sorteiosEmAndamento"></div>
    <p id="noSorteiosMessage" class="text-muted" style="display: none;">Nenhum sorteio sendo realizado no momento.</p>

    <!-- Exibição de sorteios já realizados -->
    <h3 class="mt-5">Sorteios Realizados</h3>
    <div id="sorteiosRealizados"></div>




    <!-- Animação de carregamento -->
    <div id="loadingSpinner" class="text-center mt-4" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
    </div>
</div>

<script src="{{ asset('js/sorteio/index.js') }}"></script>
<script>
    // Funções para o formulário de criação de sorteio
document.getElementById('showCreateForm').addEventListener('click', function() {
    fetch('{{ route("sorteios.create.form") }}')
        .then(response => response.text())
        .then(html => {
            document.getElementById('createFormContent').innerHTML = html;
            document.getElementById('createFormContainer').style.display = 'block';
            document.getElementById('showCreateForm').style.display = 'none';
        })
        .catch(error => console.error('Erro ao carregar o formulário:', error));
});

document.getElementById('cancelCreateForm').addEventListener('click', function() {
    document.getElementById('createFormContainer').style.display = 'none';
    document.getElementById('showCreateForm').style.display = 'block';
});

// Script para adicionar ganhadores
let ganhadores = [];

function toggleAddGanhadorForm() {
    const form = document.getElementById('addGanhadorForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function adicionarGanhador() {
    const tipoPremio = document.getElementById('premio_tipo').value;
    const quantidadePremio = document.getElementById('premio_quantidade').value;

    if (quantidadePremio > 0) {
        const posicao = ganhadores.length + 1;
        ganhadores.push({ tipo: tipoPremio, quantidade: quantidadePremio });

        const ganhadorItem = document.createElement('li');
        ganhadorItem.className = 'list-group-item';
        ganhadorItem.textContent = `${posicao}º lugar: ${quantidadePremio} ${tipoPremio === 'cambios' ? 'Câmbios' : 'HC'}`;
        document.getElementById('listaGanhadores').appendChild(ganhadorItem);

        const ganhadoresContainer = document.getElementById('ganhadoresContainer');
        const tipoInput = document.createElement('input');
        tipoInput.type = 'hidden';
        tipoInput.name = `premios[${posicao - 1}][premio_tipo]`;
        tipoInput.value = tipoPremio;

        const quantidadeInput = document.createElement('input');
        quantidadeInput.type = 'hidden';
        quantidadeInput.name = `premios[${posicao - 1}][premio_quantidade]`;
        quantidadeInput.value = quantidadePremio;

        ganhadoresContainer.appendChild(tipoInput);
        ganhadoresContainer.appendChild(quantidadeInput);

        document.getElementById('quantidade_ganhadores').value = ganhadores.length;

        document.getElementById('premio_quantidade').value = '';
        toggleAddGanhadorForm();
    } else {
        alert("Por favor, insira uma quantidade válida para o prêmio.");
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const showCreateFormButton = document.getElementById('showCreateForm');
    const cancelCreateFormButton = document.getElementById('cancelCreateForm');
    const createFormContainer = document.getElementById('createFormContainer');

    showCreateFormButton.addEventListener('click', function() {
        fetch('{{ route("sorteios.create.form") }}')
            .then(response => response.text())
            .then(html => {
                document.getElementById('createFormContent').innerHTML = html;
                createFormContainer.style.display = 'block';
                showCreateFormButton.style.display = 'none';
                cancelCreateFormButton.style.display = 'inline-block';
            })
            .catch(error => console.error('Erro ao carregar o formulário:', error));
    });

    cancelCreateFormButton.addEventListener('click', function() {
        createFormContainer.style.display = 'none';
        showCreateFormButton.style.display = 'inline-block';
        cancelCreateFormButton.style.display = 'none';
    });
});
</script>








@endsection
