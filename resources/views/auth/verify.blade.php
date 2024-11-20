@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verificação de Registro</h2>
    <p>Coloque o seguinte código em sua missão no Habbo:</p>
    <div class="alert alert-info">
        <strong>{{ $habbo_code }}</strong>
    </div>
    <p>Depois de colocar o código, clique no botão abaixo para verificar.</p>

    <form method="POST" action="{{ route('register.verifyCode') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Verificar Código</button>
    </form>
</div>
@endsection
