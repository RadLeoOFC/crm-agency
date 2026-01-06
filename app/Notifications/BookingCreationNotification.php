<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\User;
use App\Http\Controllers\UserController;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCreationNotification extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.booking_created.subject'))
            ->greeting(__('notifications.common.hello'))
            ->line(__('notifications.booking_created.line', ['user' => $this->user->name]));
    }

    public function toTelegram($notifiable)
    {
        $text =
            "*".__('notifications.common.hello_md')."*\n".
            __('notifications.booking_created.telegram', ['user' => $this->user->name]);


        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id) 
            ->content($text)
            ->options(['parse_mode' => 'Markdown']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
