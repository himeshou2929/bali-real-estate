<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Mail\Mailable;

class InquirySubmitted extends Mailable
{
    public Inquiry $inquiry;

    public function __construct(Inquiry $inquiry) { $this->inquiry = $inquiry->load('property.area'); }

    public function build()
    {
        return $this->subject('New Inquiry: '.$this->inquiry->subject.' (#'.$this->inquiry->id.')')
            ->markdown('mail.inquiries.submitted', ['inquiry' => $this->inquiry]);
    }
}