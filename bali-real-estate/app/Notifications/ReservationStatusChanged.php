<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationStatusChanged extends Notification
{
    use Queueable;
    protected $reservation;
    public function __construct($reservation){ $this->reservation=$reservation; }
    public function via($notifiable){ return ['mail']; }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reservation Status Update')
            ->line('Your reservation status changed to: '.$this->reservation->status);
    }
}