<?php

namespace App\Listeners;

use App\Events\InquiryAssigned;
use App\Services\NotificationService;

class SendNotificationOnInquiryAssigned
{
    public function __construct(protected NotificationService $service) {}

    public function handle(InquiryAssigned $event): void
    {
        $this->service->notify('assigned', $event->inquiry, [
            'assignee' => $event->assigneeName ?? null,
        ]);
    }
}