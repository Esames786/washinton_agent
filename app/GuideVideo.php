<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuideVideo extends Model
{
    use SoftDeletes;

    protected $table = 'guide_videos';

    protected $fillable = ['title', 'description', 'filename', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
