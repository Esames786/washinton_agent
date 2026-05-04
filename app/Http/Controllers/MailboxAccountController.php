<?php

namespace App\Http\Controllers;

use App\EmailAccount;
use App\User;
use App\Services\CpanelEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

/**
 * Admin screen: create / assign / manage cPanel mailbox accounts.
 * Permission 162 = "cPanel Email Management".
 */
class MailboxAccountController extends Controller
{
    public function index()
    {
        // Only admin (role=1) or users with permission 162 can access
        $this->authorizeAccess(162);

        $users = User::where('deleted', 0)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $accounts = EmailAccount::with('user:id,name,email')
            ->orderByDesc('id')
            ->paginate(25);

        return view('main.cpanel_email.index', compact('users', 'accounts'));
    }

    public function store(Request $request, CpanelEmailService $cpanel)
    {
        $this->authorizeAccess(162);
        $request->validate([
            'local_part' => ['required', 'string', 'max:190', 'regex:/^[a-zA-Z0-9][a-zA-Z0-9._-]{0,62}$/'],
            'domain'     => ['required', 'string', 'in:' . config('mailbox.default_domain')],
            'password'   => ['required', 'string', 'min:6', 'max:190'],
            'user_id'    => 'nullable|integer|exists:user,id',
            'quota_mb'   => 'nullable|integer|min:1',
            'status'     => 'required|in:active,inactive',
        ]);

        $local  = trim(strtolower($request->local_part));
        $domain = trim(strtolower($request->domain));
        $email  = $local . '@' . $domain;
        $quota  = (int) ($request->quota_mb ?? 1024);
        if ($quota <= 0) $quota = 1024;

        $cp = $cpanel->createMailbox($email, $request->password, $domain, $quota);

        if (!$cp['ok']) {
            return back()->with('err', 'cPanel create failed: ' . $cp['message'])->withInput();
        }

        EmailAccount::create([
            'user_id'         => $request->user_id,
            'email'           => $email,
            'local_part'      => $local,
            'domain'          => $domain,
            'username'        => $email,
            'password_enc'    => Crypt::encryptString($request->password),
            'imap_host'       => config('mailbox.imap_host'),
            'imap_port'       => config('mailbox.imap_port'),
            'imap_encryption' => config('mailbox.imap_encryption'),
            'smtp_host'       => config('mailbox.smtp_host'),
            'smtp_port'       => config('mailbox.smtp_port'),
            'smtp_encryption' => config('mailbox.smtp_encryption'),
            'quota_mb'        => $quota,
            'status'          => $request->status,
        ]);

        return back()->with('msg', 'Mailbox created on cPanel and saved.');
    }

    public function update(Request $request, EmailAccount $emailAccount)
    {
        $request->validate([
            'user_id' => 'nullable|integer|exists:user,id',
            'status'  => 'required|in:active,inactive',
        ]);

        $emailAccount->user_id = $request->user_id;
        $emailAccount->status  = $request->status;
        $emailAccount->save();

        return back()->with('msg', 'Email account updated.');
    }

    public function resetPassword(Request $request, EmailAccount $emailAccount, CpanelEmailService $cpanel)
    {
        $request->validate(['password' => 'required|string|min:6|max:190']);

        $cp = $cpanel->changePassword($emailAccount->email, $request->password, $emailAccount->domain);
        if (!$cp['ok']) {
            return back()->with('err', 'cPanel password change failed: ' . $cp['message']);
        }

        $emailAccount->password_enc = Crypt::encryptString($request->password);
        $emailAccount->save();

        return back()->with('msg', 'Mailbox password updated.');
    }

    public function toggleStatus(EmailAccount $emailAccount)
    {
        $emailAccount->status = $emailAccount->status === 'active' ? 'inactive' : 'active';
        $emailAccount->save();
        return back()->with('msg', 'Status updated.');
    }

    public function destroy(EmailAccount $emailAccount, CpanelEmailService $cpanel)
    {
        $this->authorizeAccess(162);
        $cp = $cpanel->deleteMailbox($emailAccount->email);
        if (!$cp['ok']) {
            return back()->with('err', 'cPanel delete failed: ' . $cp['message']);
        }
        $emailAccount->delete();
        return back()->with('msg', 'Mailbox deleted.');
    }

    /**
     * Allow access if user is admin (role=1) OR has the given permission number
     * in their active panel's access list.
     */
    private function authorizeAccess(int $permission): void
    {
        $user = \Auth::user();

        // Admin always allowed
        if ((int) $user->role === 1) return;

        // Check active panel permission
        $ptype   = \App\user_setting::where('user_id', $user->id)->value('penal_type') ?? 1;
        $column  = match ((int) $ptype) {
            2       => 'emp_access_web',
            3       => 'emp_access_test',
            4       => 'panel_type_4',
            5       => 'panel_type_5',
            6       => 'panel_type_6',
            default => 'emp_access_phone',
        };

        $access = array_filter(explode(',', (string) ($user->$column ?? '')));

        if (!in_array((string) $permission, $access, true)) {
            abort(403, 'You do not have permission to access this page.');
        }
    }
}
