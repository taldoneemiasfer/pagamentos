<!DOCTYPE html>
<html>

<head>
    <title>Retorno do Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-5">
    <div class="container">
        <h1 class="mb-4">Resultado do Pagamento</h1>

        <hr>
<!--<h5>Log de Depuração (var_dump):</h5>
<pre>
    @php
        var_dump($response);
    @endphp
</pre> -->

        @if (!empty($response['errors']))
            @foreach ($response['errors'] as $erro)
                <div class="alert alert-danger">
                    <strong>Erro:</strong> {{ $erro['description'] ?? 'Erro desconhecido' }}<br>
                    <strong>Código:</strong> {{ $erro['code'] ?? 'Sem código' }}
                </div>
            @endforeach

        @elseif (!empty($response['id']))
            <div class="alert alert-success">
                <strong>Pagamento criado com sucesso!</strong>
            </div>

            <ul class="list-group">
                <li class="list-group-item"><strong>ID:</strong> {{ $response['id'] }}</li>
                <li class="list-group-item"><strong>Tipo:</strong> {{ $response['billingType'] }}</li>
                <li class="list-group-item"><strong>Valor:</strong> R$ {{ number_format($response['value'], 2, ',', '.') }}
                </li>
                <li class="list-group-item"><strong>Status:</strong> {{ $response['status'] }}</li>
                <li class="list-group-item"><strong>Vencimento:</strong> {{ $response['dueDate'] }}</li>
                <li class="list-group-item"><strong>Link:</strong>
                    <a href="{{ $response['invoiceUrl'] }}" target="_blank">Ver Boleto/Pix</a>
                </li>
            </ul>
        @else
            <div class="alert alert-warning">
                Nenhum dado de pagamento retornado.
            </div>
        @endif

        <a href="{{ url('/') }}" class="btn btn-primary mt-4">Voltar</a>
    </div>
</body>

</html>