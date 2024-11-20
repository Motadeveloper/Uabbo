@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Informações do Habbo</h2>

    @if(isset($habboData))
        <p><strong>Nome:</strong> {{ $habboData['name'] ?? 'N/A' }}</p>
        <p><strong>Missão (Motto):</strong> {{ $habboData['motto'] ?? 'N/A' }}</p>
        <p><strong>ID Único:</strong> {{ $habboData['uniqueId'] ?? 'N/A' }}</p>
        <p><strong>Figura:</strong> {{ $habboData['figureString'] ?? 'N/A' }}</p>
        <p><strong>Membro Desde:</strong> {{ $habboData['memberSince'] ?? 'N/A' }}</p>
        <p><strong>Perfil Visível:</strong> {{ $habboData['profileVisible'] ? 'Sim' : 'Não' }}</p>

        <h4>Emblemas Selecionados:</h4>
        @if(!empty($habboData['selectedBadges']))
            <ul>
                @foreach($habboData['selectedBadges'] as $badge)
                    <li>{{ $badge['code'] }}</li>
                @endforeach
            </ul>
        @else
            <p>Sem emblemas selecionados.</p>
        @endif
    @else
        <p>Informações não encontradas.</p>
    @endif
</div>
@endsection
