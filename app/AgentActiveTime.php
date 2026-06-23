<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentActiveTime extends Model
{
    protected $table = 'agent_active_times';

    protected $fillable = ['user_id', 'work_date', 'active_seconds'];

    protected $casts = [
        'work_date'      => 'date',
        'active_seconds' => 'integer',
    ];

    /** Human readable "Xh Ym" from a seconds value. */
    public static function format(int $seconds): string
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        return ($h > 0 ? $h . 'h ' : '') . $m . 'm';
    }
}
