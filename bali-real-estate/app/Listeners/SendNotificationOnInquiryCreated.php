<?php

namespace App\Listeners;

use App\Events\InquiryCreated;
use App\Services\NotificationService;

class SendNotificationOnInquiryCreated
{
    public function __construct(protected NotificationService $service) {}

    public function handle(InquiryCreated $event): void
    {
        $this->service->notify('created', $event->inquiry);
    }
}