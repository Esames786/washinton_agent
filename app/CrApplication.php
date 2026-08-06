<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrApplication extends Model
{
    protected $table = 'cr_applications';

    public const EMPLOYMENT_WFH      = 'work_from_home';
    public const EMPLOYMENT_IN_HOUSE = 'in_house';

    protected $fillable = [
        'full_name', 'father_name', 'national_id', 'dob', 'gender', 'marital_status',
        'email', 'phone', 'country', 'city', 'state', 'address',
        'campaign', 'employment_type', 'campaign_id', 'shift_type', 'pay_type',
        'additional_info', 'campaign_experience',
        'resume_path', 'documents', 'contract_accepted_at', 'password',
        'status', 'rejection_note', 'agent_id', 'ip_address',
    ];

    protected $casts = [
        'documents'           => 'array',
        'contract_accepted_at'=> 'datetime',
        'dob'                 => 'date',
    ];

    public static array $campaigns = [
        'healthcare'    => 'Healthcare Products & Services',
        'home_security' => 'Home Security Solutions',
        'real_estate'   => 'Real Estate / Mortgage Lead Gen',
        'dme'           => 'DME — Durable Medical Equipment',
        'logistics'     => 'Logistics / Trucking Dispatch',
        'software'      => 'Software Development',
        'amazon'        => 'Amazon',
        'inhouse'       => 'In-house',
    ];

    public function getCampaignLabelAttribute(): string
    {
        // Prefer the managed campaign name; fall back to the legacy static map.
        if ($this->relationLoaded('campaign_ref') || $this->campaign_id) {
            $name = optional($this->campaign_ref)->name;
            if ($name) return $name;
        }
        return static::$campaigns[$this->campaign] ?? ucfirst((string) $this->campaign);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /** Managed campaign/job this application belongs to. */
    public function campaign_ref()
    {
        return $this->belongsTo(CrCampaign::class, 'campaign_id');
    }

    public function isWorkFromHome(): bool { return $this->employment_type === self::EMPLOYMENT_WFH; }
    public function isInHouse(): bool      { return $this->employment_type === self::EMPLOYMENT_IN_HOUSE; }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return [
            self::EMPLOYMENT_WFH      => 'Work From Home',
            self::EMPLOYMENT_IN_HOUSE => 'In-House / On-Site',
        ][$this->employment_type] ?? '—';
    }

    /** Handles both canonical values (commission_only) and legacy display strings. */
    public function getPayTypeLabelAttribute(): string
    {
        $map = [
            'salary_only'           => 'Salary Only',
            'commission_only'       => 'Commission Only',
            'salary_and_commission' => 'Salary + Commission',
        ];
        return $map[$this->pay_type] ?? ($this->pay_type ?: '—');
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }
}
