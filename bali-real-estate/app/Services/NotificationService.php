<?php

namespace App\Services;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InquiryEventNotification;

class NotificationService
{
    /**
     * @param string $event 'created'|'assigned'|'replied'|'status_changed'
     * @param \App\Models\Inquiry $inquiry
     * @param array $payload 追加情報（old_status/new_status/assignee など）
     */
    public function notify(string $event, Inquiry $inquiry, array $payload = []): void
    {
        // 送信対象: 管理者全員 + 担当者（あれば）
        $admins = User::query()->where('role', 'admin')->where('is_active', true)->get();

        $notifiables = $admins->values();

        if (!empty($inquiry->agent) && !empty($inquiry->agent->user_id)) {
            $agentUser = User::find($inquiry->agent->user_id);
            if ($agentUser && $agentUser->is_active) {
                $notifiables->push($agentUser);
            }
        }

        // 通知本体
        Notification::send(
            $notifiables->unique('id'),
            new InquiryEventNotification($event, $inquiry, $payload)
        );
    }
}