<?php

namespace App\Http\Controllers;

use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

use App\Models\Pagamentos;
use App\Models\Customers;

class PagamentoController extends Controller
{

    public function index()
    {
        $itens = Produtos::orderBy("preco", "asc")->paginate(10);

        return view('pagamento.index', compact('itens'));
    }

    public function pagar(Request $request)
    {
        print ($request);
        $forma = $request->input('forma_pagamento');

        $request->validate([
            'produtos' => ['required', 'array', 'min:1'],
            'forma_pagamento' => ['required', Rule::in(['PIX', 'CREDIT_CARD', 'BOLETO'])],
        ]);

        if ($forma === 'CREDIT_CARD') {
            $request->validate([
                'holderName' => [
                    Rule::requiredIf($forma === 'CREDIT_CARD'),
                    'regex:/^[\pL\s]+$/u'
                ],
                'number' => [
                    Rule::requiredIf($forma === 'CREDIT_CARD'),
                    'digits_between:13,19'
                ],
                'expiryMonth' => [
                    Rule::requiredIf($forma === 'CREDIT_CARD'),
                    'digits:2',
                    'integer',
                    'between:1,12'
                ],
                'expiryYear' => [
                    Rule::requiredIf($forma === 'CREDIT_CARD'),
                    'digits:4',
                    'integer'
                ],
                'cvv' => [
                    Rule::requiredIf($forma === 'CREDIT_CARD'),
                    'digits:3'
                ],
            ], [
                'holderName.regex' => 'O nome no cartão deve conter apenas letras e espaços.',
                'number.digits_between' => 'O número do cartão deve ter entre 13 e 19 dígitos.',
                'expiryMonth.between' => 'O mês deve estar entre 1 e 12.',
            ]);
        }

        $produtosSelecionados = $request->input('produtos');
        $produtos = Produtos::where('id', $produtosSelecionados)->get();


        $somaValores = $produtos->sum('preco');
        $produtos = $produtos->toArray();

        $cliente = Customers::first(); // Obtém o primeiro cliente cadastrado em teste
        if ($cliente) {
            $clienteId = $cliente->getKey();
        } else {
            // Tratar erro: nenhum cliente encontrado
        }

        //$valor = array_sum(array_column($produtosSelecionados, 'preco'));
        $dueDate = now()->addDays(7)->format('Y-m-d');
        /**
         * enviar pagamento para a API
         */

        $conteudo = [
            'customer' => $clienteId,
            'billingType' => $forma,
            'value' => $somaValores,
            'dueDate' => $dueDate,
            'description' => json_encode($produtos)
        ];

        $response = Http::withOptions(
            [
                'verify' => false, // Disable SSL verification for testing
            ]
        )->withHeaders([
                    'accept' => 'application/json',
                    'access_token' => env('API_TOKEN'),
                    'User-Agent' => 'Teste PP',
                ])->throw()->post('https://api-sandbox.asaas.com/v3/payments', $conteudo);

        $status = $response->status();
        if ($status == 200) {
            $json = $response->json();
            $pagamentoId = $json['id']; // ID do pagamento retornado pela API
            $status = $json['status']; // Status do pagamento retornado pela API
            /** criar o pagamento no banco de dados */

            $conteudo = array_merge($conteudo, [
                'pagamento_id' => $pagamentoId, // ID buscar do resultado da API
                'status' => $status, // Status do pagamento retornado pela API
            ]);
            
            Pagamentos::create($conteudo);

        } else {
            dd("Erro ao criar pagamento: " . $response->body());
        }



        return redirect("/pagamento/$forma");
    }

    public function testePagamentoBoleto()
    {
        $response = Http::withOptions(
            [
                'verify' => false, // Disable SSL verification for testing
            ]
        )->withHeaders([
                    'accept' => 'application/json',
                    'access_token' => env('API_TOKEN'),
                    'User-Agent' => 'Teste PP',
                ])->throw()->post('https://api-sandbox.asaas.com/v3/payments', [
                    'customer' => 'cus_000006866639',
                    'billingType' => 'BOLETO',
                    'value' => 100.00,
                    'dueDate' => '2025-08-01'
                ]);
        //dd($response->status(), $response->body());
        $status = $response->status();
        $body = $response->body(); // Texto bruto da resposta

        //logger("STATUS: $status");
        //logger("BODY: $body");

        $json = $response->json();
        //dd($json);
        return view('pagamento.retornoTeste', ['response' => $json]);
    }
}
