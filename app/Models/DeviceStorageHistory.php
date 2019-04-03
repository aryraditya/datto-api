<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DeviceStorageHistory extends Model
{
    protected static $unguarded = true;

    protected static function boot()
    {
        parent::boot();
        static::creating(function(self $model) {
            $model->id = Str::orderedUuid();
        });
    }
}
