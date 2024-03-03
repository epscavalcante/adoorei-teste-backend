<?php

namespace App\Models;

class SaleProduct extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'price',
        'amount',
        'total'
    ];

    protected $casts = [
        'price' => 'int',
        'amount' => 'int',
        'total' => 'int'
    ];
}
