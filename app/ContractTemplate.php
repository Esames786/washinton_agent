<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractTemplate extends Model
{
    protected $table    = 'contract_templates';
    protected $fillable = ['title', 'content', 'is_default'];

    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first();
    }
}
