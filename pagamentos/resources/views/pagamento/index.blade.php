<!DOCTYPE html>
<html>
<head>
    <title>Pagamento</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="p-4">
    <h2>Gerenciar Pagamento</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/pagamento">
        @csrf
        <div class="mb-3">
            <label>Produto</label>
            <input type="text" name="produto" class="form-control">
        </div>
        <div class="mb-3">
            <label>Forma de pagamento</label>
            <select name="forma_pagamento" class="form-control">
                <option value="">Selecione</option>
                <option value="pix">Pix</option>
                <option value="cartao">Cartão</option>
                <option value="boleto">Boleto</option>
            </select>
        </div>
        <button class="btn btn-primary">Pagar</button>
    </form>
</body>
</html>