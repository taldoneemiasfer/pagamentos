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
}
