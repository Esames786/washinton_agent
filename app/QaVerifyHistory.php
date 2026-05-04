<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QaVerifyHistory extends Model
{
    protected $table = 'qa_verify_histories';
    protected $fillable = [
        'user_id',
        'order_id',
        'pstatus',
        'verify',
        'no_of_calls',
        'negative',
        'negative_to_user_id',
        'decision',
        'remarks',
        'admin_agree',
        'admin_remarks'
    ];
    
    public function order()
    {
        return $this->belongsTo(AutoOrder::class,'order_id','id');
    }
}
