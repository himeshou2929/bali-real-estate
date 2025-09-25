<?php

namespace App\Events;

use App\Models\Inquiry;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InquiryStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(public Inquiry $inquiry, public string $oldStatus, public string $newStatus) {}
}