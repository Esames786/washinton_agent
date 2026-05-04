<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportNew extends Model
{
    protected $table = 'report_new';

    protected $fillable = [ 'userId', 'orderId', 'pstatus' ];

    // public function payment()
    // {
    //     return $this->belongsTo(orderpayment::class, 'orderId', 'id');
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
    
    public function order()
    {
        return $this->belongsTo(AutoOrder::class, 'orderId', 'id');
    }

      public function carriers()
    {
        return $this->belongsTo(carrier::class, 'orderId', 'orderId');
    }

    // public function order2()
    // {
    //     return $this->belongsTo(AutoOrder::class, 'orderId', 'id')->select('id','payment','pay_carrier','u_id','manager_id','dispatcher_id','paneltype');
    // }
}
