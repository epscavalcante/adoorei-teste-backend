<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Symfony\Component\Uid\UuidV4;

class Model extends EloquentModel
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = UuidV4::v4()->__toString();
            }
        });
    }
}
