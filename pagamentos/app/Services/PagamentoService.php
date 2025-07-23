<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class PagamentoService
{
    public function enviarPagamentoSandBox(array $dados)
    {
        $url = rtrim(env('API_URL'), '/') . '/' . trim(env('API_VERSION'), '/') . '/payments';
        $response = Http::withOptions(['verify' => false])->withHeaders([
            'accept' => 'application/json',
            'access_token' => env('API_TOKEN'),
            'User-Agent' => 'Teste PP',
        ])
            ->throw()
            ->post($url, $dados);
        return $response;
    }
    public function getBillingInfo(string $pagamentoId)
    {
        $response = Http::withOptions(['verify' => false])->withHeaders([
            'accept' => 'application/json',
            'access_token' => env('API_TOKEN'),
            'User-Agent' => 'Teste PP',
        ])
            ->get("https://api-sandbox.asaas.com/v3/payments/{$pagamentoId}/billingInfo");
        if ($response->failed()) {
            throw new \Exception('Erro ao obter informações de cobrança: ' . $response->body());
        }
        return $response;
    }    
}