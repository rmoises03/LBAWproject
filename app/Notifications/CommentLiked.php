<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentLiked extends Notification
{
    use Queueable;

    protected $post;
    protected $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct($post, $comment)
    {
        $this->post = $post;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/posts/'.$this->post->id);

        return (new MailMessage)
                    ->subject('Your comment received an upvote')
                    ->greeting('Hello '.$notifiable->username.'!')
                    ->line('Your comment in the post "'.$this->post->title.'" has received an upvote:')
                    ->line('Comment:'.$this->comment->text)
                    ->line('Current votes'.$this->comment->upvotes - $this->comment->downvotes)
                    ->action('View Post', $url)
                    ->line('Thank you for using our website!');
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
