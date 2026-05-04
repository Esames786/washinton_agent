<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryBlockCompany extends Model
{
    protected $table = "historyblockcompany";

    protected $fillable = [
        "user_id", 
        "company_id", 
        "comment",
        "status",
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
