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
}
