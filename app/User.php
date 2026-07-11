<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'slug',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'hr_employee_id',
        'verify',
        'status',
        'is_crazyrays',
    ];

    /**
     * Whether this account originated from CrazyRays Solutions
     * (crazyrayssolutions.com.pk signup form OR an approved campaign application).
     * Drives per-user branding across screens, emails, and redirects.
     */
    public function isCrazyrays(): bool
    {
        return (int) ($this->is_crazyrays ?? 0) === 1;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // =====================================================================
    // B6 — dynamic panel access (accessor-compat over user_panel_access)
    // ---------------------------------------------------------------------
    // The 6 legacy per-panel permission columns become Eloquent accessors that
    // read from the user_panel_access link table (falling back to the raw column
    // until the copy-seeder has run — so behaviour is unchanged pre-migration).
    // After every save the columns are mirrored into user_panel_access, and new
    // panels (7+) are read/written through accessForPanel()/setPanelAccess().
    // =====================================================================

    /** panel id => legacy user column */
    public const PANEL_COLUMNS = [
        1 => 'emp_access_phone',
        2 => 'emp_access_web',
        3 => 'emp_access_test',
        4 => 'panel_type_4',
        5 => 'panel_type_5',
        6 => 'panel_type_6',
    ];

    /** per-instance cache: panel_type_id => access_ids */
    protected $panelAccessMapCache = null;

    protected function panelAccessMap(): array
    {
        if ($this->panelAccessMapCache === null) {
            $this->panelAccessMapCache = [];
            if (!empty($this->attributes['id'])) {
                try {
                    $this->panelAccessMapCache = \Illuminate\Support\Facades\DB::table('user_panel_access')
                        ->where('user_id', $this->attributes['id'])
                        ->pluck('access_ids', 'panel_type_id')
                        ->all();
                } catch (\Throwable $e) {
                    $this->panelAccessMapCache = [];
                }
            }
        }
        return $this->panelAccessMapCache;
    }

    /**
     * Read a panel's access ids: link table first; if the column was just set
     * in-memory (dirty) trust that; otherwise fall back to the raw column value
     * (keeps behaviour identical before the seeder populates user_panel_access).
     */
    protected function readPanelAccess(int $panelId, $rawValue)
    {
        $col = self::PANEL_COLUMNS[$panelId] ?? null;
        if ($col && array_key_exists($col, $this->attributes) && $this->isDirty($col)) {
            return $rawValue;
        }
        $map = $this->panelAccessMap();
        return array_key_exists($panelId, $map) ? $map[$panelId] : $rawValue;
    }

    public function getEmpAccessPhoneAttribute($value) { return $this->readPanelAccess(1, $value); }
    public function getEmpAccessWebAttribute($value)   { return $this->readPanelAccess(2, $value); }
    public function getEmpAccessTestAttribute($value)  { return $this->readPanelAccess(3, $value); }
    public function getPanelType4Attribute($value)     { return $this->readPanelAccess(4, $value); }
    public function getPanelType5Attribute($value)     { return $this->readPanelAccess(5, $value); }
    public function getPanelType6Attribute($value)     { return $this->readPanelAccess(6, $value); }

    /**
     * Access-id string for ANY panel id. Panels 1-6 use their column/link value;
     * new panels (7+) read the link table, defaulting to the primary phone-panel
     * access when no custom row has been set yet.
     */
    public function accessForPanel($panelId): string
    {
        $panelId = (int) $panelId;
        if (isset(self::PANEL_COLUMNS[$panelId])) {
            return (string) $this->{self::PANEL_COLUMNS[$panelId]};
        }
        $map = $this->panelAccessMap();
        if (array_key_exists($panelId, $map)) {
            return (string) $map[$panelId];
        }
        // New city panels are order-taking panels → default to the primary (phone) access
        // until an admin customises this panel's permissions in edit_subcontractor.
        return (string) $this->emp_access_phone;
    }

    /** Set access for a new panel (7+) directly in the link table. */
    public function setPanelAccess(int $panelId, ?string $accessIds): void
    {
        if (empty($this->attributes['id'])) return;
        try {
            \Illuminate\Support\Facades\DB::table('user_panel_access')->updateOrInsert(
                ['user_id' => $this->attributes['id'], 'panel_type_id' => $panelId],
                ['access_ids' => (string) $accessIds, 'updated_at' => now(), 'created_at' => now()]
            );
            $this->panelAccessMapCache = null;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('setPanelAccess failed: ' . $e->getMessage());
        }
    }

    protected static function booted()
    {
        static::saved(function (self $user) {
            $user->syncPanelAccessToTable();
        });
    }

    /** Mirror the (changed) 6 panel columns into user_panel_access after a save. */
    public function syncPanelAccessToTable(): void
    {
        if (empty($this->attributes['id'])) return;
        try {
            foreach (self::PANEL_COLUMNS as $panelId => $col) {
                if (!array_key_exists($col, $this->attributes)) continue;
                if (!$this->wasRecentlyCreated && !$this->wasChanged($col)) continue;
                \Illuminate\Support\Facades\DB::table('user_panel_access')->updateOrInsert(
                    ['user_id' => $this->attributes['id'], 'panel_type_id' => $panelId],
                    ['access_ids' => (string) ($this->attributes[$col] ?? ''), 'updated_at' => now(), 'created_at' => now()]
                );
            }
            $this->panelAccessMapCache = null;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('syncPanelAccessToTable failed: ' . $e->getMessage());
        }
    }

    public function quote_create()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 0)->orderBy('id', 'desc');
    }

    public function order_book()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 8)->orderBy('id', 'desc');
    }
    public function order_book_and_pending()
    {
        return $this->hasMany(report::class, 'userId', 'id')->whereIn('pstatus', [7, 8, 18])->orderBy('id', 'desc');
    }

    public function order_booked()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 8)->orWhere('pstatus', 7)->orWhere('pstatus', 18)->orderBy('id', 'desc');
    }

    public function cancel_order()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 14)->orderBy('id', 'desc');
    }

    public function listed()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 9)->orderBy('id', 'desc');
    }

    public function dispatch()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 10)->orderBy('id', 'desc');
    }

    public function pickup()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 11)->orderBy('id', 'desc');
    }

    public function delivery()
    {
        return $this->hasMany(report::class, 'userId', 'id')->where('pstatus', 12)->orderBy('id', 'desc');
    }

    public function call_history()
    {
        return $this->hasMany(call_history::class, 'userId', 'id');
    }

    public function count_click()
    {
        return $this->hasMany(count_click::class, 'user_id', 'id');
    }

    public function carrier_update()
    {
        return $this->hasMany(carrier::class, 'userId', 'id');
    }

    public function history()
    {
        return $this->hasMany(order_history::class, 'user_id', 'id');
    }

    public function userRole()
    {
        return $this->belongsTo(role::class, 'role', 'id');
    }

    public function flag()
    {
        return $this->hasMany(Flag::class, 'user_id', 'id')
            ->where('status', 1);
    }

    public function revert()
    {
        return $this->hasMany(TransferQuote::class, 'original_user_id', 'id');
    }

    public function screen_shot()
    {
        return $this->hasMany(UserScreenShot::class, 'user_id', 'id')->whereDate('created_at', date('Y-m-d'))->orderBy('created_at', 'DESC');
    }

    public function ot_manager()
    {
        return $this->hasOne(OrderTakerQouteAccess::class, 'ot_ids', 'id');
    }

    public function dispatcher()
    {
        return $this->hasMany(AutoOrder::class, 'dispatcher_id', 'id')->where('pstatus', 11)->where('approve_pickup', 1);
    }

    public function delivery_boy()
    {
        return $this->hasMany(AutoOrder::class, 'delivery_boy_id', 'id')->where('pstatus', 12)->where('approve_deliver', 1);
    }

    public function daily()
    {
        return $this->hasOne(DailyQoute::class, 'user_id', 'id');
    }

    public function manager_ot()
    {
        return $this->hasMany(OrderTakerQouteAccess::class, 'manager_id', 'id');
    }

    public function daily_ass()
    {
        return $this->hasOne(DailyQoute::class, 'user_id', 'id')->where('date', date('Y-m-d'));
    }

    public function assignedData()
    {
        return $this->hasOne(AssignUsedAndNewOrderTaker::class, 'orderTaker', 'id');
    }

    public function assignedCompany()
    {
        return $this->hasMany(UsedAndNewCarDealers::class, 'user_id', 'id');
    }

    public function callCountUsedAndNew()
    {
        return $this->hasMany(CallCountUsedAndNew::class, 'user_id', 'id');
    }

    public function callCountUser()
    {
        return $this->hasMany(CallCountUsedAndNew::class, 'user_id');
    }

    public function whatsappCountUser()
    {
        return $this->hasMany(WhatsappAutoApproachCount::class, 'userId');
    }

    public function logoutQuestionComments()
    {
        return $this->hasMany(LogoutQuestionComments::class, 'user_id');
    }

    public function logoutQuestionCommentsByDate($date)
    {
        $data = $this->logoutQuestionNegative()
            ->whereDate('created_at', $date->format('Y-m-d'))
            ->whereTime('created_at', $date->format('H:i:s'))
            ->get();

        return $data;
    }

    public function logoutQuestionPositive()
    {
        return $this->hasMany(LogoutQuestionComments::class, 'user_id')->where('verified', 1);
    }

    public function logoutQuestionNegative()
    {
        return $this->hasMany(LogoutQuestionComments::class, 'user_id')->where('verified', 0);
    }

    public function logoutQuestionsAnswers()
    {
        return $this->hasMany(LogoutQuestionsAnswer::class, 'user_id', 'id');
    }

    public function commission()
    {
        return $this->hasOne(UserCommission::class, 'user_id', 'id');
    }

    public function commissions()
    {
        return $this->hasMany(UserCommission::class, 'user_id', 'id');
    }

    public function hasVerifiedPassword()
    {
        return Session::has('auth.password_confirmed_at') &&
            (time() - Session::get('auth.password_confirmed_at', 0)) < config('auth.password_timeout', 10800); // 3 hours by default
    }

    public function user_setting()
    {
        return $this->hasMany(user_setting::class, 'user_id', 'id');
    }

    public function flag_count()
    {
        return $this->hasMany(Flag::class, 'user_id', 'id');
    }

    public function chatReceiver()
    {
        return $this->hasMany(chat::class, 'touserId', 'id');
    }

    public function chatSender()
    {
        return $this->hasMany(chat::class, 'fromuserId', 'id');
    }

    public function latestChat()
    {
        return $this->hasOne(chat::class)
            ->where(function ($query) {
                $query->where('fromuserId', $this->id)
                    ->orWhere('touserId', $this->id);
            })
            ->latest('created_at');
    }

    public function assignedDataNew()
    {
        return $this->hasOne(ShipperDetailsAssign::class, 'orderTaker', 'id');
    }

    public function callCountUserNewApproach()
    {
        return $this->hasMany(ShipperDetailsPhone::class, 'userId')->where('type',1);
    }

    public function whatsappCountUserNewApproach()
    {
        return $this->hasMany(ShipperDetailsPhone::class, 'userId')->where('type',2);
    }

    //dealer
    public function assignedDataNewDealer()
    {
        return $this->hasOne(ShipperDetailsAssignDealer::class, 'orderTaker', 'id');
    }

    public function callCountUserNewApproachDealer()
    {
        return $this->hasMany(ShipperDetailsPhoneDealer::class, 'userId')->where('type',1);
    }

    public function whatsappCountUserNewApproachDealer()
    {
        return $this->hasMany(ShipperDetailsPhoneDealer::class, 'userId')->where('type',2);
    }


    public function assignedDataNewShipa()
    {
        return $this->hasOne(ShipaAssign::class, 'orderTaker', 'id');
    }

    public function callCountUserNewApproachShipa()
    {
        return $this->hasMany(ShipaQueryPhone::class, 'userId')->where('type',1);
    }

    public function whatsappCountUserNewApproachShipa()
    {
        return $this->hasMany(ShipaQueryPhone::class, 'userId')->where('type',2);
    }

    public function assignedDataNewCarrier()
    {
        return $this->hasOne(ShipperDetailsAssignCarrier::class, 'orderTaker', 'id');
    }

    public function callCountUserNewApproachCarrier()
    {
        return $this->hasMany(ShipperDetailsPhoneCarrier::class, 'userId')->where('type',1);
    }

    public function whatsappCountUserNewApproachCarrier()
    {
        return $this->hasMany(ShipperDetailsPhoneCarrier::class, 'userId')->where('type',2);
    }

    /**
     * #18 (2026-07-03): default folder access from New through Delivered for every
     * agent. Folder permission IDs (from the panel-access map):
     *   0 New, 1 Interested, 2 Follow More, 3 Asking Low, 4 Not Interested,
     *   5 No Response, 6 Time Quote, 7 Payment Missing, 8 Booked, 66 Double Booking,
     *   9 Listed, 10 Schedule, 11 Pickup, 12 Delivered.
     */
    public static function defaultFolderAccessIds(): array
    {
        return [0, 1, 2, 3, 4, 5, 6, 7, 8, 66, 9, 10, 11, 12];
    }

    /**
     * Merge the default New→Delivered folder IDs into every panel-access column so
     * the folders show regardless of which panel the agent is assigned. Idempotent.
     */
    public function applyDefaultFolderAccess(bool $save = true): void
    {
        $cols = ['emp_access_phone', 'emp_access_web', 'emp_access_test', 'panel_type_4', 'panel_type_5', 'panel_type_6'];
        $defaults = array_map('strval', self::defaultFolderAccessIds());
        foreach ($cols as $col) {
            $existing = array_filter(explode(',', (string) ($this->$col ?? '')), function ($v) {
                return $v !== '' && $v !== null;
            });
            $this->$col = implode(',', array_values(array_unique(array_merge($existing, $defaults))));
        }
        if ($save) {
            $this->save();
        }
    }
}
