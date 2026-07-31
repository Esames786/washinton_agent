<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NdaTemplate extends Model
{
    protected $table    = 'nda_templates';
    protected $fillable = ['title', 'content', 'is_default'];

    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first();
    }
}
