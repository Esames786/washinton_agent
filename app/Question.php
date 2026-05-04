<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    public function ans()
    {
        return $this->hasMany(QuestionAnwser::class,'q_id','id');
    }
}
