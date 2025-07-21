<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Customers;

class ClienteController extends Controller
{
    //Esse codigo cria um cliente de teste na API do Asaas e retorna o id do cliente na tela
    //caso de tempo, pegar o valor gerado e salvar no banco de dados
    public function criarClienteTeste()
    {
        $response = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
                    'accept' => 'application/json',
                    'access_token' => env('API_TOKEN'),
                ])->post('https://api-sandbox.asaas.com/v3/customers', [
                    'name' => 'Zé da Padoca',
                    'email' => 'teste@email.com',
                    'cpfCnpj' => '96431344073',
                    'phone' => '24989899999'

                ]);

        //converter cliente para json
        $cliente = $response->json();
        //adicionar o cliente no banco de dados
        Customers::create($cliente);
        //como nao tem view, vamos usar o dd para ver o retorno
        dd($cliente); 
    }
}

//id criado cus_000006867286