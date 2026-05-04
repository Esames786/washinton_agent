<?php

return [
    'default_domain' => env('MAILBOX_DEFAULT_DOMAIN', 'daydispatch.com'),

    'imap_host'       => env('MAILBOX_IMAP_HOST', 'mail.daydispatch.com'),
    'imap_port'       => (int) env('MAILBOX_IMAP_PORT', 993),
    'imap_encryption' => env('MAILBOX_IMAP_ENCRYPTION', 'ssl'),

    'smtp_host'       => env('MAILBOX_SMTP_HOST', 'mail.daydispatch.com'),
    'smtp_port'       => (int) env('MAILBOX_SMTP_PORT', 465),
    'smtp_encryption' => env('MAILBOX_SMTP_ENCRYPTION', 'ssl'),

    'cpanel_host'       => env('CPANEL_HOST'),
    'cpanel_user'       => env('CPANEL_USER'),
    'cpanel_token'      => env('CPANEL_TOKEN'),
    'cpanel_verify_ssl' => (bool) env('CPANEL_VERIFY_SSL', true),
];
