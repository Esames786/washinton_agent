<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * B6 — per-user, per-panel access list (the dynamic replacement for the 6 fixed
 * user columns). One row per (user_id, panel_type_id); access_ids is the same
 * comma-separated permission-id string the legacy columns held.
 */
class UserPanelAccess extends Model
{
    protected $table = 'user_panel_access';

    protected $fillable = ['user_id', 'panel_type_id', 'access_ids'];
}
