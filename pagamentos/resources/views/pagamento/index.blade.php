<!DOCTYPE html>
<html>

<head>
    <title>Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 bg-white p-4 rounded shadow">
                <h2 class="mb-4 text-center">Gerenciar Pagamento</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('pagamento.pagar') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Selecione os produtos</label>
                        @foreach ($itens as $item)
                            <div class="form-check">
                                <input type="checkbox" name="produtos[]" value="{{ $item->id }}" class="form-check-input"
                                    data-preco="{{ $item->preco }}" {{ in_array($item->id, old('produtos', [])) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $item->nome }} - R$ {{ number_format($item->preco, 2, ',', '.') }}
                                </label>
                            </div>
                        @endforeach
                        <div class="mt-2 fw-bold">
                            Total: R$ <span id="total">0,00</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Escolha a forma de pagamento</label>
                        <select name="forma_pagamento" class="form-select" id="forma_pagamento">
                            <option value="">Selecione</option>
                            <option value="PIX" {{ old('forma_pagamento') == 'PIX' ? 'selected' : '' }}>Pix</option>
                            <option value="CREDIT_CARD" {{ old('forma_pagamento') == 'CREDIT_CARD' ? 'selected' : '' }}>
                                Cartão</option>
                            <option value="BOLETO" {{ old('forma_pagamento') == 'BOLETO' ? 'selected' : '' }}>Boleto
                            </option>
                        </select>
                    </div>

                    <div id="cartaoCampos"
                        style="{{ (old('forma_pagamento') === 'CREDIT_CARD') ? 'display:block;' : 'display:none;' }}">
                        <div class="mb-3">
                            <label for="holderName" class="form-label">Nome no cartão</label>
                            <input type="text" name="holderName" id="holderName" class="form-control"
                                value="{{ old('holderName') }}">
                        </div>

                        <div class="mb-3">
                            <label for="number" class="form-label">Número do cartão</label>
                            <input type="text" name="number" id="number" class="form-control"
                                value="{{ old('number') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiryMonth" class="form-label">Mês de validade</label>
                                <input type="text" name="expiryMonth" id="expiryMonth" class="form-control"
                                    value="{{ old('expiryMonth') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="expiryYear" class="form-label">Ano de validade </label>
                                <input type="text" name="expiryYear" id="expiryYear" class="form-control"
                                    value="{{ old('expiryYear') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" name="cvv" id="cvv" class="form-control" value="{{ old('cvv') }}">
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do titular do cartão</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email do titular do cartão</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="cpfCnpj" class="form-label">CPF ou CNPJ</label>
                            <input type="text" name="cpfCnpj" id="cpfCnpj" class="form-control"
                                value="{{ old('cpfCnpj') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="postalCode" class="form-label">CEP</label>
                            <input type="text" name="postalCode" id="postalCode" class="form-control"
                                value="{{ old('postalCode') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="addressNumber" class="form-label">Número do endereço</label>
                            <input type="text" name="addressNumber" id="addressNumber" class="form-control"
                                value="{{ old('addressNumber') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="addressComplement" class="form-label">Complemento do endereço</label>
                            <input type="text" name="addressComplement" id="addressComplement" class="form-control"
                                value="{{ old('addressComplement') }}">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefone com DDD</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="mobilePhone" class="form-label">Celular</label>
                            <input type="text" name="mobilePhone" id="mobilePhone" class="form-control"
                                value="{{ old('mobilePhone') }}">
                        </div>
                    </div>


                    <button class="btn btn-primary w-100" onclick="console.log('Formulário enviado')">Pagar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
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

        const selectPagamento = document.querySelector('#forma_pagamento');
        const camposCartao = document.getElementById('cartaoCampos');
        const camposCartaoInputs = camposCartao.querySelectorAll('input');

        function atualizarCamposCartao() {
            if (selectPagamento.value === 'CREDIT_CARD') {
                camposCartao.style.display = 'block';
                camposCartaoInputs.forEach(input => {
                    if (input.hasAttribute('data-required')) {
                        input.setAttribute('required', 'required');
                    }
                });
            } else {
                camposCartao.style.display = 'none';
                camposCartaoInputs.forEach(input => {
                    if (input.hasAttribute('required')) {
                        input.removeAttribute('required');
                    }
                });
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            atualizarTotal();

            camposCartaoInputs.forEach(input => {
                if (input.hasAttribute('required')) {
                    input.setAttribute('data-required', 'true'); 
                }
            });

            atualizarCamposCartao();
        });

        selectPagamento.addEventListener('change', atualizarCamposCartao);

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', atualizarTotal);
        });
    </script>
</body>

</html>