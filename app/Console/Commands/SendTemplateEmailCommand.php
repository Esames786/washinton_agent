<?php

namespace App\Console\Commands;

use App\SendTemplateEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

use Illuminate\Support\Facades\View;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class SendTemplateEmailCommand extends Command
{
    protected $signature = 'app:send-template-email';
    protected $description = 'Send template emails with daily global limit';

    public function handle()
    {
        $windowDays = 5;
        $today = Carbon::today()->toDateString();

        $dailyLimit = $this->getTodayLimit($today);
        $remaining = $this->getRemainingQuota($today);

        if ($remaining <= 0) {
            Log::warning('Daily email limit reached, stopping send run', ['day' => $today, 'limit' => $dailyLimit]);
            return 0;
        }

        $tasks = SendTemplateEmail::with('template')->orderBy('id', 'desc')->get();

        foreach ($tasks as $task) {

            $remaining = $this->getRemainingQuota($today);
            if ($remaining <= 0) {
                Log::warning('Daily email limit reached mid-run, stopping', ['day' => $today]);
                break;
            }

            $effectiveLimit = (int)($task->limit_data ?? 0);
            if ($effectiveLimit > 0) {
                $effectiveLimit = min($effectiveLimit, $remaining);
            } else {
                $effectiveLimit = $remaining;
            }

            $sql = $this->decorateSql($task->query_sql, $effectiveLimit, $windowDays);
            $rows = DB::select($sql);

            if (empty($rows)) {
                SendTemplateEmail::where('id', $task->id)->delete();
                continue;
            }

            $to = [];
            if (in_array($task->data_type, [1,2,3,4,5])) {
                foreach ($rows as $r) {
                    if (!empty($r->email)) {
                        $r->template = $task->template;
                        $r->send_template_id = $task->id;
                        $to[$r->id] = $r;
                        if (count($to) >= $effectiveLimit) break;
                    }
                }
            } elseif (in_array($task->data_type, [6])) {
                foreach ($rows as $r) {
                    $r->email = $r->oemail ?? null;
                    $r->name  = $r->oname  ?? null;
                    if (!empty($r->email)) {
                        $r->template = $task->template;
                        $r->send_template_id = $task->id;
                        $to[$r->id] = $r;
                        if (count($to) >= $effectiveLimit) break;
                    }
                }
            }

            if (empty($to)) {
                SendTemplateEmail::where('id', $task->id)->delete();
                continue;
            }

            $remaining = $this->getRemainingQuota($today);
            if ($remaining <= 0) {
                Log::warning('Daily email limit reached before send', ['day' => $today]);
                break;
            }

            if (count($to) > $remaining) {
                $to = array_slice($to, 0, $remaining, true);
            }

            $successIds = $this->BrevoEmailSender($to);

            SendTemplateEmail::where('id', $task->id)->delete();

            if (!empty($successIds)) {
                $this->incrementSentCount($today, count($successIds));

                $baseTable = $this->extractBaseTable($task->query_sql);
                if ($baseTable) {
                    foreach (array_chunk($successIds, 500) as $chunk) {
                        DB::table($baseTable)->whereIn('id', $chunk)->update(['email_send_time' => now()]);
                    }
                } else {
                    Log::warning('Could not determine base table to update email_send_time', ['send_template_email_id' => $task->id]);
                }
            }
        }

        return 0;
    }

    private function decorateSql(string $sql, int $limit, int $windowDays): string
    {
        $trimmed = rtrim(rtrim($sql), ';');

        $wrapped = "SELECT * FROM ({$trimmed}) AS _t
            WHERE (_t.email_send_time IS NULL OR _t.email_send_time < DATE_SUB(NOW(), INTERVAL {$windowDays} DAY))";

        if ($limit > 0) {
            $wrapped .= " LIMIT {$limit}";
        }

        return $wrapped;
    }

    private function extractBaseTable(string $sql): ?string
    {
        $oneLine = preg_replace('/\s+/', ' ', trim($sql));

        if (preg_match('/\bfrom\s*\(/i', $oneLine)) {
            return null;
        }

        if (preg_match('/\bfrom\s+(`?)([a-z0-9_\.]+)\1/i', $oneLine, $m)) {
            $table = $m[2];
            if (str_contains($table, '.')) {
                $parts = explode('.', $table, 2);
                $table = $parts[1];
            }
            return trim($table, '`');
        }

        return null;
    }

    private function BrevoEmailSender(array $to): array
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('services.brevo.key'));

        $api = new TransactionalEmailsApi(new Client(), $config);

        $templateColumns = ['name','email','phone'];
        $success = [];

        foreach ($to as $user_id => $userData) {
            $template = $userData->template;
            $template_body = $template->description ?? '';
            $personalized_body = $template_body;

            foreach ($templateColumns as $column) {
                $placeholder = '{' . $column . '}';
                $replacement = $userData->$column ?? '';
                if (str_contains($personalized_body, $placeholder)) {
                    $personalized_body = str_replace($placeholder, $replacement, $personalized_body);
                }
            }

            $view = View::make('emails.emailnotification', [
                'personalized_body' => $personalized_body,
                'image' => $template->template_image ?? null
            ])->render();

            $email = $userData->email ?? null;
            if (empty($email)) {
                continue;
            }

            $u_name = !empty($userData->name) ? $userData->name : 'Washington User';

            $fromEmail = 'contact@shipa1.com';
            $fromName  = 'Washington Update';
            $subject   = 'Washington Important';

            // 1) Try Brevo first
            try {
                $send = new SendSmtpEmail([
                    'to'          => [['email' => $email, 'name' => $u_name]],
                    'subject'     => $subject,
                    'htmlContent' => $view,
                    'sender'      => ['name' => $fromName, 'email' => $fromEmail],
                ]);

                $response = $api->sendTransacEmail($send);

                $success[] = $userData->id ?? $user_id;

                Log::info('Brevo email sent', [
                    'message_id' => is_array($response) ? ($response['messageId'] ?? null) : null,
                    'user_id' => $user_id,
                    'email' => $email,
                ]);

                continue;
            } catch (\Throwable $e) {
                Log::warning('Brevo failed, trying Laravel mailer', [
                    'user_id' => $user_id,
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
            }

            // 2) Fallback: Laravel default mailer
            try {
                Mail::send([], [], function (Message $message) use ($email, $u_name, $fromEmail, $fromName, $subject, $view) {
                    $message->to($email, $u_name)
                        ->from($fromEmail, $fromName)
                        ->subject($subject)
                        ->html($view);
                });

                $success[] = $userData->id ?? $user_id;

                Log::info('Laravel mailer email sent (fallback)', [
                    'user_id' => $user_id,
                    'email' => $email,
                ]);
            } catch (\Throwable $e2) {
                Log::error('Laravel mailer fallback failed', [
                    'user_id' => $user_id,
                    'email' => $email,
                    'error' => $e2->getMessage(),
                ]);
            }
        }

        return $success;
    }



    private function getTodayLimit(string $day): int
    {
        $row = DB::table('daily_email_limits')->where('day', $day)->first();
        if ($row) return (int)$row->limit;

        DB::table('daily_email_limits')->insert([
            'day' => $day,
            'limit' => 1000,
            'sent_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return 1000;
    }

    private function getRemainingQuota(string $day): int
    {
        $row = DB::table('daily_email_limits')->where('day', $day)->first();

        if (!$row) {
            $limit = $this->getTodayLimit($day);
            return $limit;
        }

        $limit = (int)$row->limit;
        $sent  = (int)$row->sent_count;

        return max(0, $limit - $sent);
    }

    private function incrementSentCount(string $day, int $count): void
    {
        DB::transaction(function () use ($day, $count) {
            $row = DB::table('daily_email_limits')->where('day', $day)->lockForUpdate()->first();

            if (!$row) {
                DB::table('daily_email_limits')->insert([
                    'day' => $day,
                    'limit' => 1000,
                    'sent_count' => $count,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return;
            }

            $newCount = ((int)$row->sent_count) + $count;

            DB::table('daily_email_limits')
                ->where('day', $day)
                ->update([
                    'sent_count' => $newCount,
                    'updated_at' => now(),
                ]);
        });
    }
}
