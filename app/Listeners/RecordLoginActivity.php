<?php

namespace App\Listeners;

use App\UserLoginActivity;
use Illuminate\Auth\Events\Login;

/**
 * Batch 6 (D1): capture login IP for EVERY successful login — hello (LoginController trait login)
 * AND crazy (BridgeAuthController Auth::login) — via the framework Login event, so it no longer
 * depends on a per-controller hook being invoked/deployed.
 *
 * Also sets a session flag the EnforceUserSecurity middleware uses to record the (post-regenerate)
 * session id for single-session enforcement (D2).
 */
class RecordLoginActivity
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        if (!$user) {
            return;
        }

        $request = request();

        // Source: crazy logins arrive via the /bridge/* SSO routes or carry the cr_origin session tag.
        $path   = (string) ($request ? $request->path() : '');
        $source = (str_contains($path, 'bridge') || str_contains($path, 'sso') || session('cr_origin'))
            ? 'crazyrays'
            : 'hello';

        UserLoginActivity::record(
            $user->id,
            $request ? $request->ip() : null,
            $source,
            $request ? $request->userAgent() : null
        );

        // Survives the session regeneration that happens right after login; the middleware reads it
        // on the next request to store the FINAL session id (avoids the pre/post-regenerate id race).
        if ($request && $request->hasSession()) {
            $request->session()->put('b6_fresh_login', true);
        }
    }
}
