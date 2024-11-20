@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Recupere uma conta</h2>
    <p>Coloque o código a seguir na sua missão no Habbo:</p>
    <div class="instruction-card">
        <p>Confirme o código abaixo na sua missão:</p>
        <div class="code-display">
            <strong>{{ $habbo_code }}</strong>
        </div>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="form-group">
            <label for="name">Usuário</label>
            <input type="text" id="name" class="form-control" name="name" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Nova Senha</label>
            <input type="password" id="password" class="form-control" name="password" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Nova Senha</label>
            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="habbo_code">Código de Confirmação</label>
            <input type="text" id="habbo_code" class="form-control" name="habbo_code" value="{{ $habbo_code }}" readonly>
            @error('habbo_code')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Recuperar Conta</button>
    </form>
</div>
@endsection
