@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h2>Pagamento via Pix</h2>
        <p>Obrigado pela compra!</p>
        <p>Escaneie o QR Code abaixo ou copie o código Pix.</p>

        <div class="my-4">
            <img src="data:image/png;base64,{{ $encodedImage }}" alt="QR Code Pix" class="img-fluid" style="max-width: 300px;">
        </div>

        <div class="mb-3">
            <label class="form-label">Código Pix (Copia e Cola)</label>
            <input type="text" class="form-control text-center" readonly value="{{ $payload }}">
        </div>

        <p class="fw-bold">Valor: R$ {{ number_format($valor, 2, ',', '.') }}</p>
        <p class="fw-bold">Vencimento: {{ $vencimento }}</p>
    </div>
</div>
@endsection
