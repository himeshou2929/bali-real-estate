<?php

namespace App\Services;

use App\Mail\InquiryReplyMailable;
use App\Models\Inquiry;
use App\Models\InquirySend;
use App\Events\InquiryReplied;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InquiryReplyService
{
    public function sendReply(Inquiry $inquiry, string $toEmail, string $subject, string $body, ?string $signature = null): InquirySend
    {
        return DB::transaction(function () use ($inquiry, $toEmail, $subject, $body, $signature) {
            $log = InquirySend::create([
                'inquiry_id'     => $inquiry->id,
                'user_id'        => auth()->id(),
                'to_email'       => $toEmail,
                'subject'        => $subject,
                'body'           => $body,
                'template_key'   => 'inquiries.reply.default',
                'mailable_class' => \App\Mail\InquiryReplyMailable::class,
                'status'         => 'queued',
                'meta'           => ['from' => config('mail.from.address')],
            ]);

            try {
                Mail::to($toEmail)->send(new InquiryReplyMailable($inquiry, $subject, $body, $signature));

                $log->update([
                    'status'     => 'sent',
                    'sent_at'    => now(),
                    'message_id' => null, // Laravel 11 doesn't provide message ID easily
                ]);

                // inquiries テーブルにも反映
                $inquiry->forceFill([
                    'agent_reply' => $body,
                    'replied_at'  => now(),
                    'status'      => 'replied',
                ])->save();

                // イベント発火（通知用）
                event(new InquiryReplied($inquiry));

            } catch (\Throwable $e) {
                $log->update([
                    'status' => 'failed',
                    'error'  => substr($e->getMessage(), 0, 1000),
                ]);
                throw $e;
            }

            return $log;
        });
    }
}