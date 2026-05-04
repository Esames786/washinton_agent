<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogoutQuestionsAnswer extends Model
{
    protected $table = 'logout_questions_answers';

    protected $fillable = [
        'user_id',
        'logout_question_id',
        'answer',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo(LogoutQuestion::class, 'logout_question_id', 'id');
    }
    
    public function comments()
    {
        return $this->hasMany(LogoutQuestionComments::class, 'qa_id');
    }
}
