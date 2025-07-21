<?php

namespace App\Http\Controllers;

use App\Models\produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PagamentoController extends Controller
{

    public function index()
    {
        $itens = produtos::orderBy("nome","desc")->paginate(10);
    
        return view('pagamento.index', compact('itens'));
    }

    public function pagar(Request $request)
    {
        print ($request);
        $request->validate([
            'produto' => 'required',
            'forma_pagamento' => 'required'
        ]);

        $forma = $request->input('forma_pagamento');

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
        $json = json_decode($body, true); // Tenta forçar o decode

        //logger("STATUS: $status");
        //logger("BODY: $body");

        $json = $response->json();
        //dd($json);
        return view('pagamento.retornoTeste', ['response' => $json]);
    }
}
