<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait UuidHelperTrait {

    
    public function getIncrementing()
    {
        return false;
    }

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
    
    public function getKeyType()
    {
        return 'string';
    }


}
