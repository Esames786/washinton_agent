<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogoutQuestion extends Model
{
    protected $table = 'logout_questions';
    
    protected $fillable = [
            'question',
            'role',
            'status',
        ];
    
    public function user_role()
    {
        return $this->belongsTo(role::class,'role','id');
    }
}
