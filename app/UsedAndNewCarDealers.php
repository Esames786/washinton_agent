<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CallCountUsedAndNew;

class UsedAndNewCarDealers extends Model
{
    protected $table = 'used_new_car_dealers';

    protected $fillable = [
        'name', 'user_id', 'phone', 'address', 'state', 'email', 'website', 'link', 'category', 'person_name', 'phone2', 'phone3',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')
            ->select('id','name','slug','last_name');
    }
    
    public function callCount()
    {
        return $this->hasMany(CallCountUsedAndNew::class, 'company_id')->orderby('id','desc');
    }
    
    public function callCountUser()
    {
        return $this->hasMany(CallCountUsedAndNew::class, 'user_id');
    }
    
    public function history()
    {
        return $this->hasMany(HistoryUsedAndNewCall::class, 'company_id');
    }
    
    public function whatsappCallCount()
    {
        return $this->hasMany(WhatsappAutoApproachCount::class, 'approachId');
    }
    
    public function whatsappHistory()
    {
        return $this->hasMany(HistoryUsedAndNewWhatsapp::class, 'company_id');
    }
}