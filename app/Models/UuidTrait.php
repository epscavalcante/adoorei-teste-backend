<?php

namespace App\Models;
use Symfony\Component\Uid\UuidV4;

trait UuidTrait
{
    protected $keyType = 'string';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id)
                $model->id = UuidV4::v4()->__toString();
        });
    }
}
