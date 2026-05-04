<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function quote_create()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 0)->orderBy('id', 'desc');
    }

    public function order_book()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 8)->orderBy('id', 'desc');
    }
    public function order_book_and_pending()
    {
        return $this->hasMany(report::class, 'userId', 'id')->whereIn('pstatus', [7, 8, 18])->orderBy('id', 'desc');
    }

    public function order_booked()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 8)->orWhere('pstatus', 7)->orWhere('pstatus', 18)->orderBy('id', 'desc');
    }

    public function cancel_order()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 14)->orderBy('id', 'desc');
    }

    public function listed()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 9)->orderBy('id', 'desc');
    }

    public function dispatch()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 10)->orderBy('id', 'desc');
    }

    public function pickup()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 11)->orderBy('id', 'desc');
    }

    public function delivery()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 12)->orderBy('id', 'desc');
    }

    public function call_history()
    {
        return $this->hasMany(call_history::class, 'userId', 'id');
    }

    public function count_click()
    {
        return $this->hasMany(count_click::class, 'user_id', 'id');
    }

    public function carrier_update()
    {
        return $this->hasMany(carrier::class, 'userId', 'id');
    }

    public function history()
    {
        return $this->hasMany(order_history::class, 'user_id', 'id');
    }

    public function userRole()
    {
        return $this->belongsTo(role::class, 'role', 'id');
    }

    public function flag()
    {
        return $this->hasMany(Flag::class, 'user_id', 'id')
            ->where('status', 1);
    }

    public function revert()
    {
        return $this->hasMany(TransferQuote::class, 'original_user_id', 'id');
    }

    public function screen_shot()
    {
        return $this->hasMany(UserScreenShot::class, 'user_id', 'id')->whereDate('created_at', date('Y-m-d'))->orderBy('created_at', 'DESC');
    }

    public function ot_manager()
    {
        return $this->hasOne(OrderTakerQouteAccess::class, 'ot_ids', 'id');
    }

    public function dispatcher()
    {
        return $this->hasMany(AutoOrder::class, 'dispatcher_id', 'id')->where('pstatus', 11)->where('approve_pickup', 1);
    }

    public function delivery_boy()
    {
        return $this->hasMany(AutoOrder::class, 'delivery_boy_id', 'id')->where('pstatus', 12)->where('approve_deliver', 1);
    }

    public function daily()
    {
        return $this->hasOne(DailyQoute::class, 'user_id', 'id');
    }

    public function manager_ot()
    {
        return $this->hasMany(OrderTakerQouteAccess::class, 'manager_id', 'id');
    }

    public function daily_ass()
    {
        return $this->hasOne(DailyQoute::class, 'user_id', 'id')->where('date', date('Y-m-d'));
    }

    public function assignedData()
    {
        return $this->hasOne(AssignUsedAndNewOrderTaker::class, 'orderTaker', 'id');
    }

    public function assignedCompany()
    {
        return $this->hasMany(UsedAndNewCarDealers::class, 'user_id', 'id');
    }

    public function callCountUsedAndNew()
    {
        return $this->hasMany(CallCountUsedAndNew::class, 'user_id', 'id');
    }

    public function callCountUser()
    {
        return $this->hasMany(CallCountUsedAndNew::class, 'user_id');
    }

    public function whatsappCountUser()
    {
        return $this->hasMany(WhatsappAutoApproachCount::class, 'userId');
    }

    public function logoutQuestionComments()
    {
        return $this->hasMany(LogoutQuestionComments::class, 'user_id');
    }

    public function logoutQuestionCommentsByDate($date)
    {
        $data = $this->logoutQuestionNegative()
            ->whereDate('created_at', $date->format('Y-m-d'))
            ->whereTime('created_at', $date->format('H:i:s'))
            ->get();

        return $data;
    }

    public function logoutQuestionPositive()
    {
        return $this->hasMany(LogoutQuestionComments::class, 'user_id')->where('verified', 1);
    }

    public function logoutQuestionNegative()
    {
        return $this->hasMany(LogoutQuestionComments::class, 'user_id')->where('verified', 0);
    }

    public function logoutQuestionsAnswers()
    {
        return $this->hasMany(LogoutQuestionsAnswer::class, 'user_id', 'id');
    }

    public function commission()
    {
        return $this->hasOne(UserCommission::class, 'user_id', 'id');
    }

    public function commissions()
    {
        return $this->hasMany(UserCommission::class, 'user_id', 'id');
    }

    public function hasVerifiedPassword()
    {
        return Session::has('auth.password_confirmed_at') &&
            (time() - Session::get('auth.password_confirmed_at', 0)) < config('auth.password_timeout', 10800); // 3 hours by default
    }

    public function user_setting()
    {
        return $this->hasMany(user_setting::class, 'user_id', 'id');
    }

    public function flag_count()
    {
        return $this->hasMany(Flag::class, 'user_id', 'id');
    }

    public function chatReceiver()
    {
        return $this->hasMany(chat::class, 'touserId', 'id');
    }

    public function chatSender()
    {
        return $this->hasMany(chat::class, 'fromuserId', 'id');
    }

    public function latestChat()
    {
        return $this->hasOne(chat::class)
            ->where(function ($query) {
                $query->where('fromuserId', $this->id)
                    ->orWhere('touserId', $this->id);
            })
            ->latest('created_at');
    }

    public function assignedDataNew()
    {
        return $this->hasOne(ShipperDetailsAssign::class, 'orderTaker', 'id');
    }

    public function callCountUserNewApproach()
    {
        return $this->hasMany(ShipperDetailsPhone::class, 'userId')->where('type',1);
    }

    public function whatsappCountUserNewApproach()
    {
        return $this->hasMany(ShipperDetailsPhone::class, 'userId')->where('type',2);
    }

    //dealer
    public function assignedDataNewDealer()
    {
        return $this->hasOne(ShipperDetailsAssignDealer::class, 'orderTaker', 'id');
    }

    public function callCountUserNewApproachDealer()
    {
        return $this->hasMany(ShipperDetailsPhoneDealer::class, 'userId')->where('type',1);
    }

    public function whatsappCountUserNewApproachDealer()
    {
        return $this->hasMany(ShipperDetailsPhoneDealer::class, 'userId')->where('type',2);
    }


    public function assignedDataNewShipa()
    {
        return $this->hasOne(ShipaAssign::class, 'orderTaker', 'id');
    }

    public function callCountUserNewApproachShipa()
    {
        return $this->hasMany(ShipaQueryPhone::class, 'userId')->where('type',1);
    }

    public function whatsappCountUserNewApproachShipa()
    {
        return $this->hasMany(ShipaQueryPhone::class, 'userId')->where('type',2);
    }

    public function assignedDataNewCarrier()
    {
        return $this->hasOne(ShipperDetailsAssignCarrier::class, 'orderTaker', 'id');
    }

    public function callCountUserNewApproachCarrier()
    {
        return $this->hasMany(ShipperDetailsPhoneCarrier::class, 'userId')->where('type',1);
    }

    public function whatsappCountUserNewApproachCarrier()
    {
        return $this->hasMany(ShipperDetailsPhoneCarrier::class, 'userId')->where('type',2);
    }
}
