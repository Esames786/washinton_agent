<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendTemplateEmail extends Model
{
    use HasFactory;

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }
}
