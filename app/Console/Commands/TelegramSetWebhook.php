<?php

// app/Console/Commands/TelegramSetWebhook.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramSetWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Set Telegram bot webhook URL';

    public function handle(): int
    {
        $token  = config('services.telegram-bot-api.token');
        $secret = config('services.telegram-bot-api.webhook_secret');

        $base = rtrim(
            config('services.telegram-bot-api.webhook_base_url') ?: config('app.url'),
            '/'
        );

        $url = "{$base}/telegram/webhook/{$secret}";

        $this->info("Webhook URL: {$url}");

        if (!str_starts_with($url, 'https://')) {
            $this->error('Webhook URL must start with https:// . Check TELEGRAM_WEBHOOK_BASE_URL or APP_URL');
            return self::FAILURE;
        }

        $resp = Http::post("https://api.telegram.org/bot{$token}/setWebhook", [
            'url' => $url,
        ]);

        $this->info($resp->body());

        return $resp->successful() ? self::SUCCESS : self::FAILURE;
    }

}

