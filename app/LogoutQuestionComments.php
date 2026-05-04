<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogoutQuestionComments extends Model
{
    protected $table = 'logout_question_comments';

    protected $fillable = [
        'user_id',
        'qa_id',
        'comment',
        'verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function answer()
    {
        return $this->belongsTo(LogoutQuestionAnswer::class, 'qa_id', 'id');
    }
}
