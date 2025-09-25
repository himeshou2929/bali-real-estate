<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class InquiryAssigned extends Notification
{
    use Queueable;
    public function via($notifiable){ return ['slack']; }
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content("📌 Inquiry assigned to you: ".$notifiable->subject);
    }
}