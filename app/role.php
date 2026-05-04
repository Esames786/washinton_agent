<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'level'
    ];
    
    public function access()
    {
        return $this->hasOne(RoleAccess::class,'role_id','id');
    }
    
    public function users()
    {
        return $this->hasMany(User::class,'role','id')->where('deleted',0);
    }
}
