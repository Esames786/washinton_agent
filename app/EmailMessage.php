<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email_account_id', 'email_folder_id',
        'uid', 'message_id',
        'from_email', 'from_name', 'to_email',
        'subject', 'date_at',
        'seen', 'has_attachments',
        'snippet', 'body_html',
    ];

    protected $casts = [
        'seen'            => 'bool',
        'has_attachments' => 'bool',
        'date_at'         => 'datetime',
    ];

    public function folder()
    {
        return $this->belongsTo(EmailFolder::class, 'email_folder_id');
    }
}
