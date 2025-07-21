@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h2>Pagamento com Cartão</h2>
        <p>Pagamento realizado com sucesso!</p>

        <div class="alert alert-success mt-4">
            <strong>Transação aprovada!</strong><br>
            Número do cartão: **** **** **** {{ $creditCardNumber }}
        </div>
        <div class="alert alert-info">
            <strong>Detalhes do Cartão:</strong><br>
            Bandeira: {{ $creditCardBrand }}<br>
            Token do Cartão: {{ $creditCardToken }}
        </div>

        <p class="fw-bold">Valor pago: R$ {{ number_format($valor, 2, ',', '.') }}</p>
    </div>
</div>
@endsection
