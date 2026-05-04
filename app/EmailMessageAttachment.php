<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailMessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_message_id', 'attachment_index',
        'disk', 'path',
        'original_name', 'mime_type', 'size',
    ];
}
