<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutoOrder extends Model
{
    //
    protected $table = 'order';
    protected $guarded = [];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => "datetime:Y-m-d H:i:s",
    ];

    public function orderpayment()
    {
        return $this->belongsTo(orderpayment::class, 'id', 'orderId');
    }

    public function orderpayment2()
    {
        return $this->belongsTo(orderpayment::class, 'id', 'orderId')->select('id','orderId','profit');
    }

    public function payment_log()
    {
        return $this->hasMany(payment_log::class, 'orderId', 'id');

    }

    public function payment_log2()
    {
        return $this->hasOne(payment_log::class, 'orderId', 'id')->orderBy('created_at','DESC');

    }

    public function credit_card()
    {
        return $this->hasMany(orderpayment::class, 'orderId', 'id');
    }

    public function profit_data()
    {
        return $this->hasOne(profit::class, 'order_id', 'id');

    }

    public function carrier()
    {
        return $this->belongsTo(carrier::class, 'carrier_id', 'id');

    }

    public function card_data()
    {
        return $this->belongsTo(creditcard::class, 'orderId', 'id')->select('orderId', 'card_no');
    }

    public function new_status()
    {
        return $this->belongsTo(report::class, 'id', 'orderId')->where('pstatus', 0)->orderBy('id','desc');
    }
    public function reports()
    {
        return $this->hasMany(report::class, 'orderId', 'id');
    }

    public function book_status()
    {
        return $this->belongsTo(report::class, 'id', 'orderId')->where('pstatus', 8)->orderBy('id','desc');
    }

    public function lister()
    {
        return $this->belongsTo(report::class, 'id', 'orderId')->where('pstatus', 9)->orderBy('id','desc');
    }

    public function dispatcher()
    {
        return $this->belongsTo(report::class, 'id', 'orderId')->where('pstatus', 10)->orderBy('id','desc');
    }

    public function completer()
    {
        return $this->belongsTo(report::class, 'id', 'orderId')->where('pstatus', 13)->orderBy('id','desc');
    }

    public function listed_sheet()
    {
        return $this->hasMany(SheetDetails::class, 'orderId', 'id')->where('pstatus', 9)->orderBy('id','DESC');
    }

    public function dispatch_sheet()
    {
        return $this->hasMany(SheetDetails::class, 'orderId', 'id')->where('pstatus', 10)->orderBy('id','DESC');
    }

    public function pickedup_sheet()
    {
        return $this->hasMany(SheetDetails::class, 'orderId', 'id')->where('pstatus', 11)->orderBy('id','DESC');
    }

    public function delivery_sheet()
    {
        return $this->hasMany(SheetDetails::class, 'orderId', 'id')->where('pstatus', 12)->orderBy('id','DESC');
    }

    public function completed_sheet()
    {
        return $this->hasMany(SheetDetails::class, 'orderId', 'id')->where('pstatus', 13)->orderBy('id','DESC');
    }

    public function filterHistory()
    {
        return $this->hasOne(order_history::class, 'order_id','id')
        ->select('id','order_id','user_id')->orderBy('id','DESC');
    }

    public function filterHistoryMany()
    {
        return $this->hasMany(order_history::class, 'order_id','id')
        ->select('id','order_id','user_id')->orderBy('id','DESC');
    }

    public function orderTaker()
    {
        return $this->belongsTo(User::class,'order_taker_id','id')
        ->select('id','name','slug','last_name');
    }

    public function orderBooker()
    {
        return $this->belongsTo(User::class,'u_id','id')
        ->select('id','name','slug','last_name');
    }

    public function qna()
    {
        return $this->hasMany(QNAOrder::class,'order_id','id');
    }

    public function latestHistory()
    {
        return $this->hasOne(call_history::class, 'orderId','id')->orderBy('id','DESC');
    }

    public function cancel_history()
    {
        return $this->hasOne(call_history::class, 'orderId','id')->where('pstatus',14)->orderBy('id','DESC');
    }

    public function notRespond()
    {
        return $this->hasMany(NotRespondOrder::class,'order_id','id');
    }

    public function sheet()
    {
        return $this->hasMany(SheetDetails::class, 'orderId', 'id')->orderBy('id','DESC');
    }

    public function transfer()
    {
        return $this->hasMany(TransferQuote::class,'order_id','id');
    }

    public function email()
    {
        return $this->hasOne(OrderWebsiteEmail::class,'order_id','id');
    }

    public function feedback()
    {
        return $this->hasOne(OrderFeedback::class,'order_id','id');
    }

    public function storage()
    {
        return $this->belongsTo(storage::class,'storage_id','id');
    }

    public function dispatcher_user()
    {
        return $this->belongsTo(User::class,'dispatcher_id','id')->select('id','name','last_name');
    }

    public function carriers()
    {
        return $this->hasMany(carrier::class, 'orderId', 'id');

    }

    public function onecarrier()
    {
        return $this->hasOne(carrier::class, 'orderId', 'id')->where('who_pickup',1)->orderBy('id','DESC');

    }

    public function qa_remarks()
    {
        return $this->hasMany(QaVerifyHistory::class,'order_id','id');
    }

    public function req_ship()
    {
        return $this->hasMany(RequestShipment::class,'order_id','id');
    }

    public function instruction()
    {
        return $this->hasMany(SpecialInstruction::class,'order_id','id')->orderBy('created_at','DESC');
    }

    public function driver_nos()
    {
        return $this->hasMany(SheetDetails::class, 'orderId', 'id')->where('pstatus', 11)->orWhere('pstatus', 12)->orderBy('id','DESC');
    }

    public function approach()
    {
        return $this->hasOne(call_history::class, 'orderId', 'id')->where('approach', 1);
    }
    public function freight()
    {
        return $this->belongsTo(order_freight::class,'id','order_id');
    }

    public function carrierApproachings()
    {
        return $this->belongsTo(CarrierApproaching::class, 'id', 'order_id')->latest('created_at');
    }

    public function carrierApproachingsCount()
    {
        return $this->hasMany(CarrierApproaching::class, 'order_id', 'id');
    }

    public function customerNature()
    {
        return $this->hasMany(NatureOfCustomer::class, 'order_id', 'id');
    }

    public function portTrackHistory()
    {
        return $this->hasMany(PortTrackHistory::class, 'order_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(AutoOrder::class);
    }
    public function callCount()
    {
        return $this->hasMany(count_click::class, 'order_id');
    }

    public function history()
    {
        return $this->hasMany(call_history::class, 'userId','approaching_user')->where('approach',1)->orderby('id','desc');
    }

    public function order_pstatus()
    {
        return $this->belongsTo(PStatus::class,'pstatus','id');
    }
}
