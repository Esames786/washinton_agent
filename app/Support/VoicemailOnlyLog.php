<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

/**
 * Dedicated, noise-free log for the R-Dialer voicemail pipeline (RingCentralService
 * and RingCentralApiController write here). Goes to storage/logs/voicemail.log via an
 * on-demand channel so it never needs a config/logging.php entry. Every method is
 * fail-safe: a logging problem must never break a voicemail sync.
 *
 * @method static void debug(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 */
class VoicemailOnlyLog
{
    public static function __callStatic($level, $arguments)
    {
        $message = (string) ($arguments[0] ?? '');
        $context = (array) ($arguments[1] ?? []);
        $level   = in_array($level, ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'], true)
            ? $level : 'info';

        try {
            Log::build([
                'driver' => 'single',
                'path'   => storage_path('logs/voicemail.log'),
                'level'  => 'debug',
            ])->{$level}($message, $context);
        } catch (\Throwable $e) {
            try {
                Log::{$level}('[voicemail] ' . $message, $context);
            } catch (\Throwable $ignored) {
                // never let logging break the caller
            }
        }
    }
}
