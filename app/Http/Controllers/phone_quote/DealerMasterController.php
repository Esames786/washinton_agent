<?php

namespace App\Http\Controllers\phone_quote;

use App\DealerMaster;
use App\DealerMasterAssign;
use App\DealerMasterHistory;
use App\DealerMasterPhone;
use App\Http\Controllers\Controller;
use App\Http\Controllers\phone_quote\neworder\NewOrder;
use App\Template;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Dealer Master — the DealerRater list, worked the same way as Carrier Approaching.
 *
 * Kept in its own controller rather than adding an eleventh `_dealermaster` block to NewOrder.php
 * (already ~10k lines): the dataset is separate, its tables are separate, and nothing here needs
 * anything from the order pipeline.
 *
 * Access follows the carrier convention of three codes rather than one, so "can see the list",
 * "can hand records out" and "can run the report" are separate grants:
 *
 *   181  Dealer Master (view)
 *   182  Dealer Master Assign
 *   183  Dealer Master Filter / Report
 *
 * Roles 1 (super admin) and 9 bypass, matching the carrier screen.
 */
class DealerMasterController extends Controller
{
    public const PERM_VIEW   = 181;
    public const PERM_ASSIGN = 182;
    public const PERM_FILTER = 183;

    /** Roles that see everything regardless of assignment — same pair the carrier screen uses. */
    private const ADMIN_ROLES = [1, 9];

    private function newOrder(): NewOrder
    {
        return app(NewOrder::class);
    }

    /** Does the signed-in user hold this permission on their CURRENT panel? */
    private function allowed(int $code): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }
        if (in_array((int) $user->role, self::ADMIN_ROLES, true)) {
            return true;
        }

        // accessForPanel() covers the city panels 7+ (Onsite etc.) as well as columns 1-6.
        $ptype = $this->newOrder()->check_user_setting($user->id);

        return in_array((string) $code, explode(',', $user->accessForPanel($ptype)), true);
    }

    private function isAdmin(): bool
    {
        return Auth::check() && in_array((int) Auth::user()->role, self::ADMIN_ROLES, true);
    }

    // ── The screen ───────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        if (! Auth::check()) {
            return redirect('/loginn/');
        }
        if (! $this->allowed(self::PERM_VIEW)) {
            return redirect('/dashboard')->with('error', 'You do not have access to Dealer Master.');
        }

        $state = '';
        $query = DealerMaster::query();

        if (! $this->isAdmin()) {
            // An agent only ever sees what was handed to them.
            $query->where('user_id', Auth::id());

            $assign = Auth::user()->assignedDataNewDealerMaster;
            if ($assign) {
                $state = $assign->state;
                // has_phone is stamped at import, so this is one indexed column instead of four
                // OR'd phone tests on 24k rows.
                $query->where('has_phone', (int) $assign->with_phone === 1 ? 1 : 0);
            }
        }

        $data     = $query->orderBy('id', 'DESC')->paginate(20);
        $template = Template::all();
        $states   = $this->stateList();

        return view('main.phone_quote.DealerMaster.index', compact('data', 'state', 'template', 'states'));
    }

    /** Filter — returns just the table partial, the way the carrier screen reloads by AJAX. */
    public function search(Request $request)
    {
        if (! Auth::check()) {
            return redirect('/loginn/');
        }
        if (! $this->allowed(self::PERM_VIEW)) {
            abort(403, 'Not authorized.');
        }

        $user  = Auth::user();
        $state = '';
        $query = DealerMaster::query();

        // "Search as" — an admin can look at the list through one agent's assignment.
        if ((string) $request->input('search_as') === '2' || ! $this->isAdmin()) {
            $query->where('user_id', $user->id);
            $assign = $user->assignedDataNewDealerMaster;
            if ($assign) {
                $state = $assign->state;
                $query->where('has_phone', (int) $assign->with_phone === 1 ? 1 : 0);
            }
        }

        if ($request->filled('state')) {
            $query->where('states', $request->state);
        }
        if ($request->filled('orderTaker')) {
            $query->where('user_id', $request->orderTaker);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', '%' . $s . '%')
                  ->orWhere('email', 'LIKE', '%' . $s . '%')
                  ->orWhere('city', 'LIKE', '%' . $s . '%')
                  ->orWhere('phone', 'LIKE', '%' . $s . '%');
            });
        }
        if ($request->input('w_phone') !== null && $request->input('w_phone') !== '') {
            $query->where('has_phone', (string) $request->w_phone === '1' ? 1 : 0);
        }
        if ($request->input('email') !== null && $request->input('email') !== '') {
            (string) $request->email === '1'
                ? $query->whereNotNull('email')
                : $query->whereNull('email');
        }
        if ($request->input('withHistory') !== null && $request->input('withHistory') !== '') {
            (string) $request->withHistory === '1'
                ? $query->has('history')
                : $query->doesntHave('history');
        }
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', (float) $request->min_rating);
        }
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('created_at', [
                date('Y-m-d 00:00:00', strtotime($request->startDate)),
                date('Y-m-d 23:59:59', strtotime($request->endDate)),
            ]);
        }

        $data = $query->orderBy('id', 'DESC')->paginate(20)->appends($request->query());

        return view('main.phone_quote.DealerMaster.table', compact('data', 'state'));
    }

    // ── Assignment ───────────────────────────────────────────────────────────

    /** Admin view: every dealer, plus the form that hands records to an agent. */
    public function all(Request $request)
    {
        if (! Auth::check()) {
            return redirect('/loginn/');
        }
        if (! $this->allowed(self::PERM_ASSIGN)) {
            return redirect('/dashboard')->with('error', 'You do not have access to Dealer Master assignment.');
        }

        $data    = DealerMaster::with('user')->orderBy('id', 'DESC')->paginate(20);
        $agents  = User::where('deleted', 0)->where('role', '!=', 1)
            ->orderBy('name')->get(['id', 'name', 'last_name', 'slug']);
        $states  = $this->stateList();
        $assigns = DealerMasterAssign::with('orderTakerUser')->get();

        return view('main.phone_quote.DealerMaster.all', compact('data', 'agents', 'states', 'assigns'));
    }

    /**
     * Hand a batch of dealers to an agent.
     *
     * Only ever picks up UNASSIGNED rows (`user_id = 0`), so re-running this can never take a
     * dealer away from the agent already working it.
     */
    public function storeAssign(Request $request)
    {
        if (! Auth::check()) {
            return redirect('/loginn/');
        }
        if (! $this->allowed(self::PERM_ASSIGN)) {
            abort(403, 'Not authorized.');
        }

        $request->validate([
            'orderTaker'     => 'required|integer|exists:user,id',
            'state'          => 'required|array|min:1',
            'recordsAllowed' => 'required|integer|min:1|max:100000',
            'w_phone2'       => 'required|in:0,1',
        ]);

        DealerMasterAssign::updateOrCreate(
            ['orderTaker' => $request->orderTaker],
            [
                'state'          => implode(',', $request->state),
                'type'           => DealerMaster::TYPE,
                'with_phone'     => (int) $request->w_phone2,
                'recordsAllowed' => $request->recordsAllowed,
                'status'         => 1,
            ]
        );

        $assigned = 0;
        foreach ($request->state as $st) {
            $assigned += DealerMaster::where('user_id', 0)
                ->where('states', $st)
                ->where('has_phone', (int) $request->w_phone2)
                ->limit((int) $request->recordsAllowed)
                ->update(['user_id' => (int) $request->orderTaker]);
        }

        return back()->with('success', $assigned . ' dealer(s) assigned.');
    }

    // ── Call history ─────────────────────────────────────────────────────────

    public function history(Request $request)
    {
        if (! Auth::check() || ! $this->allowed(self::PERM_VIEW)) {
            abort(403, 'Not authorized.');
        }

        return DealerMasterHistory::with('user')
            ->where('company_id', $request->company_id)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function storeHistory(Request $request)
    {
        if (! Auth::check() || ! $this->allowed(self::PERM_VIEW)) {
            abort(403, 'Not authorized.');
        }

        $request->validate([
            'CompanyID'     => 'required|integer|exists:dealer_masters,id',
            'connectStatus' => 'nullable|string|max:255',
            'comment'       => 'nullable|string',
        ]);

        DealerMasterHistory::create([
            'user_id'       => Auth::id(),
            'company_id'    => $request->CompanyID,
            'connectStatus' => $request->connectStatus,
            'comment'       => $request->comment,
        ]);

        // Carrier behaviour: once a note is saved the record goes back to the pool so it can be
        // handed to someone else. Same rule here, deliberately, so the two screens behave alike.
        DealerMaster::where('id', $request->CompanyID)->update(['user_id' => 0]);

        return DealerMasterHistory::with('user')
            ->where('company_id', $request->CompanyID)
            ->orderBy('id', 'DESC')
            ->get();
    }

    /** One row per call / WhatsApp press — drives the counters on the row. */
    public function phoneCount(Request $request)
    {
        if (! Auth::check() || ! $this->allowed(self::PERM_VIEW)) {
            abort(403, 'Not authorized.');
        }

        $request->validate([
            'approachId' => 'required|integer|exists:dealer_masters,id',
            'type'       => 'nullable|in:1,2',
        ]);

        DealerMasterPhone::create([
            'userId'     => Auth::id(),
            'approachId' => $request->approachId,
            'type'       => (int) ($request->type ?: DealerMasterPhone::TYPE_CALL),
            'status'     => 1,
        ]);

        return response()->json([
            'success' => true,
            'calls'   => DealerMasterPhone::where('approachId', $request->approachId)
                ->where('type', DealerMasterPhone::TYPE_CALL)->count(),
        ]);
    }

    /** Inline save of the fields an agent can fill in (the source has no email addresses). */
    public function saveField(Request $request)
    {
        if (! Auth::check() || ! $this->allowed(self::PERM_VIEW)) {
            abort(403, 'Not authorized.');
        }

        $request->validate([
            'id'     => 'required|integer|exists:dealer_masters,id',
            'email'  => 'nullable|email|max:191',
            'phone'  => 'nullable|string|max:191',
            'phone2' => 'nullable|string|max:191',
            'phone3' => 'nullable|string|max:191',
            'phone4' => 'nullable|string|max:191',
        ]);

        $fields = array_filter(
            $request->only(['email', 'phone', 'phone2', 'phone3', 'phone4']),
            fn ($v) => $v !== null
        );

        if ($fields) {
            $dealer = DealerMaster::find($request->id);
            $dealer->fill($fields);
            // Keep the flag honest — an agent typing a number makes the dealer callable.
            $dealer->has_phone = (int) (bool) array_filter(
                [$dealer->phone, $dealer->phone2, $dealer->phone3, $dealer->phone4]
            );
            $dealer->save();
        }

        return response()->json(['success' => true]);
    }

    // ── Report ───────────────────────────────────────────────────────────────

    /** Assigned-records report: who holds what, and what they have done with it. */
    public function assignedReport(Request $request)
    {
        if (! Auth::check()) {
            return redirect('/loginn/');
        }
        if (! $this->allowed(self::PERM_FILTER)) {
            return redirect('/dashboard')->with('error', 'You do not have access to the Dealer Master report.');
        }

        $query = DealerMaster::with(['user', 'history.user', 'callCount'])
            ->where('user_id', '!=', 0);

        if ($request->filled('orderTaker')) {
            $query->where('user_id', $request->orderTaker);
        }
        if ($request->filled('state')) {
            $query->where('states', $request->state);
        }
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $from = date('Y-m-d 00:00:00', strtotime($request->startDate));
            $to   = date('Y-m-d 23:59:59', strtotime($request->endDate));
            $query->whereHas('history', fn ($q) => $q->whereBetween('created_at', [$from, $to]));
        }
        if ((string) $request->input('called') === '1') {
            $query->has('callCount');
        } elseif ((string) $request->input('called') === '0') {
            $query->doesntHave('callCount');
        }

        $data   = $query->orderBy('id', 'DESC')->paginate(20)->appends($request->query());
        $agents = User::where('deleted', 0)->orderBy('name')->get(['id', 'name', 'last_name', 'slug']);
        $states = $this->stateList();

        return view('main.phone_quote.DealerMaster.report', compact('data', 'agents', 'states'));
    }

    /** States actually present in the data — no point offering one with zero dealers. */
    private function stateList(): array
    {
        // Two-letter codes only: a couple of column-shifted source rows put a ZIP and a "YES"
        // in this column, and they were showing up as choices in the dropdowns.
        return DealerMaster::whereRaw("states REGEXP '^[A-Z]{2}$'")
            ->distinct()->orderBy('states')->pluck('states')->all();
    }
}
