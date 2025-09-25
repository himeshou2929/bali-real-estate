<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquiryReplyMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Inquiry $inquiry,
        public string $subjectLine,
        public string $bodyText,
        public ?string $signature = null
    ) {}

    public function build()
    {
        $property = $this->inquiry->property ?? null;

        return $this->subject($this->subjectLine)
            ->markdown('mail.inquiries.reply', [
                'inquiry'   => $this->inquiry,
                'property'  => $property,
                'bodyText'  => $this->bodyText,
                'signature' => $this->signature,
            ]);
    }
}
