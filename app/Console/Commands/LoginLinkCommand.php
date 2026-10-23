<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * php artisan login:link someone@example.com [--minutes=10]
 *
 * Prints a one-time, expiring sign-in link for that account, for support and debugging. It
 * replaces the old /login_mohsin route (fixed password, anyone on the internet could become the
 * admin): only someone who can run commands on the server can make a link, each link works once,
 * and every link made or used is logged. See App\Http\Controllers\LoginLinkController.
 */
class LoginLinkCommand extends Command
{
    protected $signature = 'login:link {email : The account to sign in as} {--minutes=10 : How long the link stays valid}';

    protected $description = 'Print a one-time, expiring sign-in link for an account (support / debugging)';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->where('deleted', 0)->first();
        if (!$user) {
            $this->error('No active account with that email.');

            return self::FAILURE;
        }

        $minutes = max(1, min(60, (int) $this->option('minutes')));
        $nonce   = Str::random(40);
        Cache::put('login_link:' . $nonce, $user->id, now()->addMinutes($minutes));

        $url = URL::temporarySignedRoute('login.link', now()->addMinutes($minutes), ['nonce' => $nonce]);

        Log::warning('login:link created', ['user_id' => $user->id, 'email' => $user->email, 'minutes' => $minutes]);

        $this->line("Sign-in link for {$user->email} (#{$user->id}), valid {$minutes} min, works once:");
        $this->line($url);

        return self::SUCCESS;
    }
}
