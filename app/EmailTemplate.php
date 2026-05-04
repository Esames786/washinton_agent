<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_template';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'banner',
        'status',
        'type',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
