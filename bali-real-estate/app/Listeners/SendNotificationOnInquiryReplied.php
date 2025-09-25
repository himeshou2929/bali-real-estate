<?php

namespace App\Listeners;

use App\Events\InquiryReplied;
use App\Services\NotificationService;

class SendNotificationOnInquiryReplied
{
    public function __construct(protected NotificationService $service) {}

    public function handle(InquiryReplied $event): void
    {
        $this->service->notify('replied', $event->inquiry);
    }
}