<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferQuote extends Model
{
    protected $table = 'transfer_quotes';
    protected $fillable = [
        'order_id',
        'original_user_id',
        'transferred_user_id',
        'original_dispatcher_id',
        'transfer_dispatcher_id',
        'old_pstatus'
    ];
    
    public function order()
    {
        return $this->belongsTo(AutoOrder::class,'order_id','id')->select(['id','pstatus','order_taker_id','dispatcher_id']);
    }
}
