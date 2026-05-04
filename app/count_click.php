<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class count_click extends Model
{
    protected $table = 'count_clicks';

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => "datetime:Y-m-d H:i:s",
        'updated_at' => "datetime:Y-m-d H:i:s",
    ];

    protected $fillable = [ 'user_id', 'order_id', 'pstatus', 'client_email', 'client_name', 'total_clicks' ];
    
    public function order(){
        return $this->belongsTo(AutoOrder::class,'order_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
