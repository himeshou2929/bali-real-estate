<?php

namespace App\Listeners;

use App\Events\InquiryStatusChanged;
use App\Services\NotificationService;

class SendNotificationOnInquiryStatusChanged
{
    public function __construct(protected NotificationService $service) {}

    public function handle(InquiryStatusChanged $event): void
    {
        $this->service->notify('status_changed', $event->inquiry, [
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
        ]);
    }
}