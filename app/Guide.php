<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guide extends Model
{
    
    use SoftDeletes;
    
    protected $table = 'guide';
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'guide_type',
        'page_name',
        'page_route',
        'thumbnail',
        'guide_content',
        'deleted_by'
        ];
        
    public function user()
    {
        return $this->belongsTo(User::class,'deleted_by','id');
    }
}
