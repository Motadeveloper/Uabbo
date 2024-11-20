@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Buscar Informações do Habbo</h2>
    <form method="POST" action="{{ route('habbo.fetchInfo') }}">
        @csrf
        <div class="form-group">
            <label for="username">Nome de Usuário do Habbo:</label>
            <input type="text" id="username" name="username" class="form-control" required autocomplete="username">
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Buscar Informações</button>
    </form>
</div>
@endsection
