<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamentos extends Model
{
    protected $table = "pagamentos";
    protected $fillable = [
        'pagamento_id',
        'customer',
        'value',
        'status',
        'billingType',
        'dueDate',
        'description',
    ];
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public static function criarPagamento($pagamento){
        Pagamentos::create($pagamento);
        return Pagamentos::where('pagamento_id', $pagamento['pagamento_id'])->first();
    }
    public static function getPagamentoPendente($clienteId, $forma, $valor)
    {
        return self::where('customer', $clienteId)
            ->where('billingType', $forma)
            ->where('value', $valor)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->first();
    }
}
