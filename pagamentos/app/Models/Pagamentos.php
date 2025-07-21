<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamentos extends Model
{
    protected $table = "pagamentos";
    protected $fillable = [
        'customer',
        'value',
        'status',
        'billingType',
        'dueDate',
        'description'
    ];
    public $timestamps = true; 
}
