<?php

namespace App\Models;

class Product extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
    ];

    protected $casts = [
        'price' => 'int',
    ];
}
