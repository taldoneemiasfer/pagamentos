<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\PagamentoController;
use App\Http\Controllers\ProdutosController;

Route::get('/', function () {
    return view('welcome');
});

/*Criacao das rotas de get e post*/
Route::get('/index', [PagamentoController::class, 'index']);
Route::post('/pagamento', [PagamentoController::class, 'pagar'])->name('pagamento.pagar');


/*Criacao de rota de teste*/
Route::get('/testePagamentoBoleto', [PagamentoController::class, 'testePagamentoBoleto']);

/*Rota para criar cliente teste fixo*/ 
Route::get('/criarClienteTeste', [ClienteController::class, 'criarClienteTeste']);

/** Rota para criar produto teste fixo */
Route::get('/criarProdutosTeste', [ProdutosController::class, 'criarProdutosTeste']);