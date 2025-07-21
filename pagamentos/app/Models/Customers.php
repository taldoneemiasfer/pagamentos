<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'cpfCnpj'
    ];

    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public static function getCustomerById($id)
    {
        return self::find($id);
    }

    /** apenas para pegar o primeiro em teste */
    public static function getFirstCustomer()
    {
        return self::orderBy("created_at", "asc")->first();
    }

    /**
     * para pegar a primeira chave direto, em teste.
     */
    public static function getFirstCustomerKey()
    {
        $customer = self::getFirstCustomer();
        return $customer ? $customer->getKey() : null;
    }
}
