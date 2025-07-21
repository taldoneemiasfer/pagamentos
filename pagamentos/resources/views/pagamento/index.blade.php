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
            <label>Selecione os produtos</label>
            @foreach ($itens as $item)
                <div class="form-check">
                    <input type="checkbox" name="produtos[]" value="{{ $item->id }}" class="form-check-input"
                        data-preco="{{ $item->preco }}" {{ in_array($item->id, old('produtos', [])) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $item->nome }} - R$
                        {{ number_format($item->preco, 2, ',', '.') }}</label>
                </div>
            @endforeach
            <div class="mb-3">
                <strong>Total: R$ <span id="total">0,00</span></strong>
            </div>
        </div>

        <div class="mb-3">
            <label>Escolha a forma de pagamento</label>
            <select name="forma_pagamento" class="form-control" id="forma_pagamento">
                <option value="">Selecione</option>
                <option value="PIX" {{ old('forma_pagamento') == 'PIX' ? 'selected' : '' }}>Pix</option>
                <option value="CREDIT_CARD" {{ old('forma_pagamento') == 'CREDIT_CARD' ? 'selected' : '' }}>Cartão</option>
                <option value="BOLETO" {{ old('forma_pagamento') == 'BOLETO' ? 'selected' : '' }}>Boleto</option>
            </select>
        </div>

        <div class="mb-3" id="cartaoCampos" style="{{ (old('forma_pagamento') === 'CREDIT_CARD') ? 'display:block;' : 'display:none;' }}">
            <div class="mb-3">
                <label for="holderName" class="form-label">Nome no cartão</label>
                <input type="text" name="holderName" id="holderName" class="form-control"
                    value="{{ old('holderName') }}">
            </div>

            <div class="mb-3">
                <label for="number" class="form-label">Número do cartão</label>
                <input type="text" name="number" id="number" class="form-control" value="{{ old('number') }}">
            </div>

            <div class="mb-3">
                <label for="expiryMonth" class="form-label">Mês de validade</label>
                <input type="text" name="expiryMonth" id="expiryMonth" class="form-control"
                    value="{{ old('expiryMonth') }}">
            </div>

            <div class="mb-3">
                <label for="expiryYear" class="form-label">Ano de validade</label>
                <input type="text" name="expiryYear" id="expiryYear" class="form-control"
                    value="{{ old('expiryYear') }}">
            </div>

            <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" name="cvv" id="cvv" class="form-control" value="{{ old('cvv') }}">
            </div>
        </div>

        <button class="btn btn-primary">Pagar</button>
    </form>
</body>
<script>
    // Função para formatar número no padrão brasileiro (ex: 10,50)
    function formatarPreco(valor) {
        return valor.toFixed(2).replace('.', ',');
    }

    const checkboxes = document.querySelectorAll('input[name="produtos[]"]');
    const totalSpan = document.getElementById('total');

    function atualizarTotal() {
        let total = 0;
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                total += parseFloat(checkbox.dataset.preco);
            }
        });
        totalSpan.textContent = formatarPreco(total);
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', atualizarTotal);
    });

    // para forma de pagamento, quando for cartao, mostra os campos de cartao
    const selectPagamento = document.querySelector('select[name="forma_pagamento"]');
    const camposCartao = document.getElementById('cartaoCampos');

    
    selectPagamento.addEventListener('change', function () {
        if (this.value === 'CREDIT_CARD') {
            camposCartao.style.display = 'block';
        } else {
            camposCartao.style.display = 'none';
        }
    });

    window.addEventListener('DOMContentLoaded', atualizarTotal);
    window.addEventListener('DOMContentLoaded', () => {
        if (selectPagamento.value === 'CREDIT_CARD') {
            camposCartao.style.display = 'block';
        }
    });
</script>

</html>