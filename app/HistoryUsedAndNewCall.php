<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryUsedAndNewCall extends Model
{
    protected $table = 'historyusedandnew';

    protected $fillable = [
        'user_id',
        'company_id',
        'connectStatus',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
