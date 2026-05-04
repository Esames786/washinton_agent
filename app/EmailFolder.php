<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_account_id', 'key', 'label', 'imap_name',
        'cached_unread', 'cached_total', 'last_synced_at',
    ];

    public function account()
    {
        return $this->belongsTo(EmailAccount::class, 'email_account_id');
    }
}
