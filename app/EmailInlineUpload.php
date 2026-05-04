<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailInlineUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'disk', 'path', 'url',
        'original_name', 'mime_type', 'size',
        'token', 'used_at',
    ];
}
