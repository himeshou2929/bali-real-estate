<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InquiryClosed extends Notification
{
    use Queueable;
    protected $inquiry;
    public function __construct($inquiry){ $this->inquiry=$inquiry; }
    public function via($notifiable){ return ['mail']; }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your inquiry has been closed')
            ->line('Status: closed')
            ->line('Subject: '.$this->inquiry->subject);
    }
}