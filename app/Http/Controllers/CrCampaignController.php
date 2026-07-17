<?php

namespace App\Http\Controllers;

use App\CrCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Admin management for CrazyRays campaigns / jobs (employment-split feature).
 * Access mirrors CrazyRays Applications: role == 1 OR permission 166.
 * `key` is the stable slug; it is generated once on create and never changed
 * afterwards so existing cr_applications.campaign links stay valid.
 */
class CrCampaignController extends Controller
{
    private const PERMISSION_CODE = '166';

    private const PAY_TYPES = ['commission_only', 'salary_only', 'salary_and_commission'];

    private function hasAccess(): bool
    {
        $user = Auth::user();
        if ((int) $user->role === 1) return true;

        $setting = \App\user_setting::where('user_id', $user->id)->first();
        $ptype   = $setting ? (int) $setting->penal_type : 1;

        return in_array(self::PERMISSION_CODE, explode(',', (string) $user->accessForPanel($ptype)));
    }

    /**
     * PUBLIC — active campaigns for the CrazyRays application form.
     * Optional ?employment_type=work_from_home|in_house filter.
     */
    public function publicList(Request $request)
    {
        $query = CrCampaign::active()->orderBy('sort_order')->orderBy('name');

        $type = $request->query('employment_type');
        if (in_array($type, [CrCampaign::CATEGORY_WFH, CrCampaign::CATEGORY_IN_HOUSE], true)) {
            $query->forCategory($type);
        }

        $campaigns = $query->get(['id', 'key', 'name', 'description', 'icon', 'employment_category', 'default_pay_type'])
            ->map(fn ($c) => [
                'id'                  => $c->id,
                'key'                 => $c->key,
                'name'                => $c->name,
                'description'         => $c->description,
                'icon'                => $c->icon,
                'employment_category' => $c->employment_category,
                'default_pay_type'    => $c->default_pay_type,
            ]);

        return response()->json(['campaigns' => $campaigns]);
    }

    public function index()
    {
        abort_unless($this->hasAccess(), 403);

        $campaigns = CrCampaign::orderBy('employment_category')
            ->orderBy('sort_order')->orderBy('name')->get();

        return view('main.cr_campaigns.index', compact('campaigns'));
    }

    public function store(Request $request)
    {
        abort_unless($this->hasAccess(), 403);

        $validator = Validator::make($request->all(), [
            'name'                => 'required|string|max:150',
            'employment_category' => 'required|in:work_from_home,in_house',
            'default_pay_type'    => 'nullable|in:' . implode(',', self::PAY_TYPES),
            'icon'                => 'nullable|string|max:16',
            'description'         => 'nullable|string|max:255',
            'status'              => 'nullable|in:0,1',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator, 'campaign')->withInput();
        }

        // Work From Home is always Commission Only.
        $payType = $request->employment_category === CrCampaign::CATEGORY_WFH
            ? 'commission_only'
            : ($request->default_pay_type ?: null);

        // Stable unique key from the name.
        $base = Str::slug($request->name, '_') ?: 'campaign';
        $key  = $base; $i = 1;
        while (CrCampaign::where('key', $key)->exists()) {
            $key = $base . '_' . $i++;
        }

        CrCampaign::create([
            'key'                 => $key,
            'name'                => trim($request->name),
            'description'         => $request->description,
            'icon'                => $request->icon,
            'employment_category' => $request->employment_category,
            'default_pay_type'    => $payType,
            'allowed_shifts'      => null,
            'status'              => $request->has('status') ? (int) $request->status : 1,
            'sort_order'          => (int) CrCampaign::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Campaign created.');
    }

    public function update(Request $request, $id)
    {
        abort_unless($this->hasAccess(), 403);

        $campaign = CrCampaign::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'                => 'required|string|max:150',
            'employment_category' => 'required|in:work_from_home,in_house',
            'default_pay_type'    => 'nullable|in:' . implode(',', self::PAY_TYPES),
            'icon'                => 'nullable|string|max:16',
            'description'         => 'nullable|string|max:255',
            'status'              => 'nullable|in:0,1',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator, 'campaign_' . $id)->withInput();
        }

        // key is intentionally NOT changed (keeps existing application links valid).
        $campaign->name                = trim($request->name);
        $campaign->description         = $request->description;
        $campaign->icon                = $request->icon;
        $campaign->employment_category = $request->employment_category;
        $campaign->default_pay_type    = $request->employment_category === CrCampaign::CATEGORY_WFH
            ? 'commission_only'
            : ($request->default_pay_type ?: null);
        $campaign->status              = $request->has('status') ? (int) $request->status : $campaign->status;
        $campaign->save();

        return back()->with('success', 'Campaign updated.');
    }

    /** Soft toggle active/inactive — inactive campaigns never appear on the form,
     *  but old applications keep their history. Campaigns are never deleted here. */
    public function toggle(Request $request, $id)
    {
        abort_unless($this->hasAccess(), 403);

        $campaign = CrCampaign::findOrFail($id);
        $campaign->status = $campaign->status ? 0 : 1;
        $campaign->save();

        return back()->with('success', 'Campaign ' . ($campaign->status ? 'activated' : 'deactivated') . '.');
    }
}
