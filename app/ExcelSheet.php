<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcelSheet extends Model
{
    protected $table = 'excel_sheets';
    protected $fillable = [
        'link',
        'type',
        'status'
    ];
}
