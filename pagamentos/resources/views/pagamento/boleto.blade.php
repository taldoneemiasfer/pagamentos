@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h2>Pagamento via Boleto</h2>
        
        <p>Use o link abaixo para acessar seu boleto bancário ou copie o código gerado.</p>

        <div class="my-4">
            <p class="lead">{{ $identificationField }}</p>
            <a href="{{ $urlBoleto }}" target="_blank" class="btn btn-primary">
                Visualizar Boleto
            </a>
        </div>

        <p class="fw-bold">Nosso número: {{ $nossoNumero }}<br>
        Valor: R$ {{ number_format($valor, 2, ',', '.') }}</p>
    </div>
</div>
@endsection
