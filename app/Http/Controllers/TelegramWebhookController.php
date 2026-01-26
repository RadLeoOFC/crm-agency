<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request, string $secret)
    {
        \Log::info('TG UPDATE', $request->all());

        // 1) защита вебхука
        if ($secret !== config('services.telegram-bot-api.webhook_secret')) {
            abort(403);
        }

        $update = $request->all();

        $message = $update['message'] ?? $update['edited_message'] ?? null;
        if (!$message) {
            return response()->json(['ok' => true]);
        }

        // Только личка (не группы)
        $chatId = $message['chat']['id'] ?? null;
        $chatType = $message['chat']['type'] ?? null;
        if (!$chatId || $chatType !== 'private') {
            return response()->json(['ok' => true]);
        }

        $fromId = $message['from']['id'] ?? null;

        // 2) если прислали контакт — привязываем
        if (isset($message['contact'])) {
            $contact = $message['contact'];

            // ВАЖНО: контакт должен принадлежать отправителю (иначе можно привязать чужой номер)
            if (($contact['user_id'] ?? null) !== $fromId) {
                $this->sendMessage($chatId, "Пожалуйста, отправьте *свой* контакт кнопкой ниже.", true);
                $this->sendShareContactKeyboard($chatId);
                return response()->json(['ok' => true]);
            }

            $phoneE164 = $this->normalizePhoneE164($contact['phone_number'] ?? '');

            if (!$phoneE164) {
                $this->sendMessage($chatId, "Не удалось распознать номер телефона. Попробуйте ещё раз.", false);
                $this->sendShareContactKeyboard($chatId);
                return response()->json(['ok' => true]);
            }

            $user = User::where('phone', $phoneE164)->first();


            if (!$user) {
                $this->sendMessage(
                    $chatId,
                    "Пользователь с таким номером не найден в CRM.\nЗарегистрируйтесь на сайте, используя *тот же номер*.",
                    true
                );
                return response()->json(['ok' => true]);
            }

            // Привязываем chat_id
            $user->telegram_chat_id = (string)$chatId;
            // можно сохранить и telegram user id (опционально)
            // $user->telegram_user_id = (string)$fromId;
            $user->save();

            $this->sendMessage($chatId, "✅ Telegram успешно привязан. Теперь вы будете получать уведомления.", false);

            return response()->json(['ok' => true]);
        }

        // 3) если /start или /connect — показываем кнопку “Поделиться контактом”
        $text = trim((string)($message['text'] ?? ''));

        if ($text === '/start' || $text === '/connect' || str_starts_with($text, '/start')) {
            $this->sendMessage(
                $chatId,
                "Привет! Чтобы привязать Telegram к аккаунту CRM, нажмите кнопку ниже и отправьте ваш номер телефона.",
                false
            );
            $this->sendShareContactKeyboard($chatId);

            return response()->json(['ok' => true]);
        }

        // дефолт: подсказка
        $this->sendMessage($chatId, "Напишите /start чтобы привязать Telegram.", false);

        return response()->json(['ok' => true]);
    }

    private function sendShareContactKeyboard(int $chatId): void
    {
        $keyboard = [
            'keyboard' => [
                [
                    [
                        'text' => '📱 Поделиться контактом',
                        'request_contact' => true,
                    ]
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ];

        $this->sendMessage($chatId, "Нажмите кнопку *Поделиться контактом*.", true, $keyboard);
    }

    private function sendMessage(int $chatId, string $text, bool $markdown = false, ?array $replyMarkup = null): void
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        if ($markdown) {
            $payload['parse_mode'] = 'Markdown';
        }

        if ($replyMarkup) {
            $payload['reply_markup'] = json_encode($replyMarkup, JSON_UNESCAPED_UNICODE);
        }

        Http::post('https://api.telegram.org/bot' . config('services.telegram-bot-api.token') . '/sendMessage', $payload);
    }

    private function normalizePhoneE164(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (!$digits) return null;

        // Telegram обычно присылает уже с кодом страны, но без "+"
        // Превращаем в "+<digits>"
        return '+' . $digits;
    }

}
