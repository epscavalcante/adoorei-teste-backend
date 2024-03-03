<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'id',
        'total',
        'status',
    ];

    protected $casts = [
        'total' => 'int',
    ];

    public function productIds(): HasMany
    {
        return $this->hasMany(
            related: SaleProduct::class,
            foreignKey: 'sale_id',
            localKey: 'id'
        );
    }
}
