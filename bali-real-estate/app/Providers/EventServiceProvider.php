<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\InquiryCreated::class => [
            \App\Listeners\SendNotificationOnInquiryCreated::class,
        ],
        \App\Events\InquiryAssigned::class => [
            \App\Listeners\SendNotificationOnInquiryAssigned::class,
        ],
        \App\Events\InquiryReplied::class => [
            \App\Listeners\SendNotificationOnInquiryReplied::class,
        ],
        \App\Events\InquiryStatusChanged::class => [
            \App\Listeners\SendNotificationOnInquiryStatusChanged::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}