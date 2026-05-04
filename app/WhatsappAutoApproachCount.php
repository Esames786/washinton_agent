<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WhatsappAutoApproachCount extends Model
{
    protected $table = 'whatsapp_autoApproach_count';
    
    protected $fillable = [
        'userId',
        'approachId',
    ];
    
    public function autosApproach()
    {
        return $this->belongsTo(UsedAndNewCarDealers::class,'approachId','id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}