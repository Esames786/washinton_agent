<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockPhone extends Model
{
    protected $table = 'block_phones';
    
    protected $fillable = [
        'user_id',
        'approver',
        'phone',
        'description',
        'status',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function approved_by()
    {
        return $this->belongsTo(User::class,'approver','id');
    }
}
