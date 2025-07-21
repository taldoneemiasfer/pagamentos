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
        'cpfCnpj',
        'updated_at',
        'created_at'
    ];

    public $timestamps = true;
}
