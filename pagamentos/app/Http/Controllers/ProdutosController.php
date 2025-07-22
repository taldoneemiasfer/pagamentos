<?php

namespace App\Http\Controllers;
use App\Models\Produtos;

class ProdutosController extends Controller
{
    public function criarProdutosTeste()
    {
        $produtos = [
            ['nome' => 'Produto 1', 'preco' => 10.50, 'descricao' => 'Descrição 1'],
            ['nome' => 'Produto 2', 'preco' => 25.00, 'descricao' => 'Descrição 2'],
            ['nome' => 'Produto 3', 'preco' => 15.75, 'descricao' => 'Descrição 3'],
        ];

        foreach ($produtos as $produto) {
            Produtos::create($produto);
        }

        dd($produtos);
        return response()->json($produtos);
    }
}