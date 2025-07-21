<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'preco',
        'descricao',
    ];

    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public static function getProdutosById($produtos)
    {
        return self::whereIn('id', $produtos)->get();
    }

    public static function getSomaProdutos($produtos)
    {
        $produtosSelecionados = self::getProdutosById($produtos);
        return $produtosSelecionados->sum('preco');
    }
}
