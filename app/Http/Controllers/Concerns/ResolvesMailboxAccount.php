<?php

namespace App\Http\Controllers\Concerns;

use App\EmailAccount;
use Illuminate\Support\Facades\Auth;

/**
 * #7 (2026-07-03): Unified mailbox.
 *
 * Admin (role 1) / manager (role 9) can "view as" any agent by storing the chosen
 * EmailAccount id in the session ('mailbox_view_as'). Every mailbox read AND send path
 * resolves the active account through resolveMailboxAccount(), so it transparently
 * operates on the selected agent's mailbox (send-as is allowed per product decision).
 *
 * Regular agents always resolve to their OWN account — the session key is only honoured
 * for supervisors, so a non-supervisor can never operate on someone else's mailbox even
 * if the session value were somehow set.
 */
trait ResolvesMailboxAccount
{
    protected function isMailboxSupervisor($user = null): bool
    {
        $user = $user ?: Auth::user();
        return $user && in_array((int) $user->role, [1, 9], true);
    }

    /**
     * The EmailAccount whose mailbox is currently being operated on:
     * the supervisor's selected "view as" account, otherwise the current user's own.
     */
    protected function resolveMailboxAccount(): ?EmailAccount
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        if ($this->isMailboxSupervisor($user)) {
            $viewAsId = session('mailbox_view_as');
            if ($viewAsId) {
                $acc = EmailAccount::where('id', $viewAsId)->where('status', 'active')->first();
                if ($acc) {
                    return $acc;
                }
                // Stale / deactivated selection — drop it and fall back to own mailbox.
                session()->forget('mailbox_view_as');
            }
        }

        return EmailAccount::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
    }

    /** All active accounts a supervisor may switch into (eager-loads the owner). */
    protected function switchableMailboxAccounts()
    {
        return EmailAccount::with('user')
            ->where('status', 'active')
            ->orderBy('email')
            ->get();
    }
}
