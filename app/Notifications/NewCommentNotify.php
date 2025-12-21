<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotify extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $article_title, public int $article_id)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
{
    if ($notifiable->role === 'moderator') {
        return ['database', 'mail']; // модеру идёт и почта, и база
    }

    return ['database']; // ридерам только база
}


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
{
    return (new MailMessage)
        ->subject("Новый комментарий к статье")
        ->line("Добавлен комментарий к статье: {$this->article_title}")
        ->action('Посмотреть статью', url("/article/{$this->article_id}"))
        ->line('Спасибо, что пользуетесь нашим сайтом!');
}


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'article'=>$this->article_title,
            'article_id'=>$this->article_id,
        ];
    }
}
