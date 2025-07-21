<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagamentoRequest;
use App\Models\Produtos;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Client\RequestException;


use App\Models\Pagamentos;
use App\Models\Customers;
use Redirect;

class PagamentoController extends Controller
{

    public function index()
    {
        $itens = Produtos::orderBy("preco", "asc")->paginate(10);

        return view('pagamento.index', compact('itens'));
    }
    public function pagar(PagamentoRequest $request)
    {
        /** para fazer a validação */
        $dadosValidados = $request->validated();
        $forma = $request->input('forma_pagamento');

        $produtosSelecionados = $request->input('produtos');
        $somaValores = Produtos::getSomaProdutos($produtosSelecionados);
        $produtos = Produtos::getProdutosById($produtosSelecionados)->toArray();

        $clienteId = Customers::getFirstCustomerKey(); // Obtém o primeiro cliente cadastrado em teste
        if (!$clienteId) {
            return redirect()->back()->withErrors(['Nenhum cliente encontrado.']);
        }

        $dueDate = now()->addDays(7)->format('Y-m-d');

        $pagamentoExists = Pagamentos::getPagamentoPendente($clienteId, $forma, $somaValores);
        //print_r($pagamentoExists);
        /**verifica se o pagamento ja existe no banco, e retorna o pagamento sem gerar duplicidade */
        if ($pagamentoExists) {
            // Se já existe um pagamento pendente, redireciona para a página de sucesso
            return $this->redirecionaPagamento($forma, $pagamentoExists->pagamento_id, $somaValores);
        }

        $conteudo = [
            'customer' => $clienteId,
            'billingType' => $forma,
            'value' => $somaValores,
            'dueDate' => $dueDate,
            'description' => json_encode($produtos)
        ];

        if ($forma === 'CREDIT_CARD') {
            $conteudo = array_merge($conteudo, [
                'creditCard' => [
                    'holderName' => $request->input('holderName'),
                    'number' => $request->input('number'),
                    'expiryMonth' => $request->input('expiryMonth'),
                    'expiryYear' => $request->input('expiryYear'),
                    'ccv' => $request->input('cvv'), // cuidado: no JSON é "ccv", não "cvv"
                ],
                'creditCardHolderInfo' => [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'cpfCnpj' => $request->input('cpfCnpj'),
                    'postalCode' => $request->input('postalCode'),
                    'addressNumber' => $request->input('addressNumber'),
                    'phone' => $request->input('phone'),
                    'mobilePhone' => $request->input('mobilePhone'),
                ]
            ]);
        }

        try {
            /** envia um post para o asaas */

            /** envia um post para o asaas */
            $response = Http::withOptions(['verify' => false])->withHeaders([
                'accept' => 'application/json',
                'access_token' => env('API_TOKEN'),
                'User-Agent' => 'Teste PP',
            ])->throw()->post('https://api-sandbox.asaas.com/v3/payments', $conteudo);

            $json = $response->json();

            $pagamentoId = $json['id'];
            $status = $json['status'];

            /** criar o pagamento no banco de dados */
            $conteudo = array_merge($conteudo, [
                'pagamento_id' => $pagamentoId,
                'status' => $status,
            ]);

            /** criacao do registro no banco de dados local */
            $pagamentoExists = Pagamentos::create($conteudo);

            //redireciona para a view de pagamento
            return $this->redirecionaPagamento($forma, $pagamentoId, $somaValores);
        } catch (RequestException $e) {
            $response = $e->response;
            if ($response && $response->status() == 400) {
                $json = $response->json();
                foreach ($json['errors'] ?? [] as $error) {
                    if ($error['code'] === 'invalid_creditCard') {
                        return redirect()->back()->withErrors(['creditCard' => $error['description']])->withInput();;
                    }
                }
                return redirect()->back()->withErrors(['api_error' => $json['message'] ?? 'Erro na API'])->withInput();
            }
            throw $e;
        }
    }

    public function redirecionaPagamento(string $forma, string $pagamentoId, float $somaValores)
    {
        if ($pagamentoId == "") {
            return redirect()->back()->withErrors(['Erro ao processar pagamento.'])->withInput();;
        }
        $response = $this->processarPagamento("https://api-sandbox.asaas.com/v3/payments/{$pagamentoId}/billingInfo");
        $json = $response->json();
        $status = $response->status();
        if ($status !== 200) {
            return redirect()->back()->withErrors(['Erro ao obter QR Code Pix.'])->withInput();;
        }
        $pix = $json['pix'];
        $creditCard = $json['creditCard'];
        $boleto = $json['bankSlip'];
        switch ($forma) {
            case 'PIX':
                return view('pagamento.pix', [
                    'encodedImage' => $pix['encodedImage'],
                    'payload' => $pix['payload'],
                    'valor' => $somaValores,
                    'vencimento' => $pix['expirationDate'],
                ]);
            case 'CREDIT_CARD':
                return view('pagamento.cartao', [
                    'creditCardNumber' =>$creditCard["creditCardNumber"],
                    'creditCardBrand' => $creditCard['creditCardBrand'],
                    'creditCardToken' => $creditCard['creditCardToken'],
                    'valor' => $somaValores
                ]);
            case 'BOLETO':
                return view('pagamento.boleto', [
                    'identificationField' => $boleto['identificationField'],
                    'urlBoleto' => $boleto['bankSlipUrl'],
                    'nossoNumero' => $boleto['nossoNumero'],
                    'barCode' => $boleto['barCode'],
                    'valor' => $somaValores,
                    'encodedImage' => $pix['encodedImage'],
                    'payload' => $pix['payload'],
                    'vencimento' => $pix['expirationDate'],
                ]);
        }
    }

    public function processarPagamento(string $url): Response
    {
        $response = Http::withOptions(
            [
                'verify' => false, // Disable SSL verification for testing
            ]
        )->withHeaders([
                    'accept' => 'application/json',
                    'access_token' => env('API_TOKEN'),
                    'User-Agent' => 'Teste PP',
                ])->throw()->get("$url");
        return $response;
    }

    public function formatarBoleto(string $codigoBarras): string
    {
        // Formata o código de barras do boleto
        $codigoBarras = preg_replace('/\D/', '', $codigoBarras); // Remove caracteres não numéricos

        $codigoFormatado = substr($codigoBarras, 0, 5) . '.' .
            substr($codigoBarras, 5, 5) . ' ' .
            substr($codigoBarras, 10, 5) . '.' .
            substr($codigoBarras, 15, 6) . ' ' .
            substr($codigoBarras, 21, 5) . '.' .
            substr($codigoBarras, 26, 6) . ' ' .
            substr($codigoBarras, 32, 1) . ' ' .
            substr($codigoBarras, 33);
        return $codigoFormatado;
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
        //$json = json_decode($body, true); // Tenta forçar o decode

        //logger("STATUS: $status");
        //logger("BODY: $body");

        $json = $response->json();
        //dd($json);
        return view('pagamento.retornoTeste', ['response' => $json]);
    }
}
