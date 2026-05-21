<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrApplication extends Model
{
    protected $table = 'cr_applications';

    protected $fillable = [
        'full_name', 'father_name', 'national_id', 'dob', 'gender', 'marital_status',
        'email', 'phone', 'country', 'city', 'state', 'address',
        'campaign', 'shift_type', 'pay_type', 'additional_info', 'campaign_experience',
        'resume_path', 'documents', 'contract_accepted_at', 'password',
        'status', 'rejection_note', 'agent_id',
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
    ];

    public function getCampaignLabelAttribute(): string
    {
        return static::$campaigns[$this->campaign] ?? ucfirst($this->campaign);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }
}
