<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\PagamentoController;

Route::get('/', function () {
    return view('welcome');
});

/*Criacao das rotas de get e post*/
Route::get('/index', [PagamentoController::class, 'index']);
Route::post('/pagamento', [PagamentoController::class, 'pagar']);

/*criacao de retorno do tipo do pagamento*/ 
Route::get('/pagamento/pix', fn() => 'Pagamento PIX');
Route::get('/pagamento/cartao', fn() => 'Pagamento Cartão');
Route::get('/pagamento/boleto', fn() => 'Pagamento Boleto');

/*Criacao de rota de teste*/
Route::get('/testePagamentoBoleto', [PagamentoController::class, 'testePagamentoBoleto']);

/*Rota para criar cliente teste fixo*/ 
Route::get('/criarClienteTeste', [ClienteController::class, 'criarClienteTeste']);