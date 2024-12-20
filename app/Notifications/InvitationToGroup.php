<?php

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvitationToGroup extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     */
    public function __construct(public Group $group, public string $token)
    {

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


        return (new MailMessage)
            ->line('You have been invited to join the group' . $this->group->name)
            ->action('Join the Group', url(route('group.approveInvitation', ['group' => $this->group->slug, 'token' => $this->token])))
            ->line('The link will be valid for next 24 hours');
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
