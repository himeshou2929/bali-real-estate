<?php

namespace App\Notifications;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class InquiryEventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $event,            // 'created'|'assigned'|'replied'|'status_changed'
        public Inquiry $inquiry,
        public array $payload = []
    ) {}

    public function via($notifiable): array
    {
        $channels = ['mail'];
        // Slack を .env の設定有無で切替
        if (config('services.slack.webhook_url')) {
            $channels[] = 'slack';
        }
        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url("/admin/inquiries/{$this->inquiry->id}");
        $subject = match ($this->event) {
            'created'       => '【新規問い合わせ】' . $this->inquiry->subject,
            'assigned'      => '【担当アサイン】' . $this->inquiry->subject,
            'replied'       => '【返信完了】' . $this->inquiry->subject,
            'status_changed'=> '【ステータス変更】' . $this->inquiry->subject,
            default         => '【問い合わせ更新】' . $this->inquiry->subject,
        };

        $line2 = match ($this->event) {
            'created'       => '新規問い合わせが登録されました。',
            'assigned'      => '担当者がアサインされました。',
            'replied'       => '返信が送信されました。',
            'status_changed'=> "ステータスが {$this->payload['old_status']} → {$this->payload['new_status']} に変更されました。",
            default         => '問い合わせに更新がありました。',
        };

        return (new MailMessage)
            ->subject($subject)
            ->line($line2)
            ->line('件名：' . $this->inquiry->subject)
            ->action('管理画面で確認', $url);
    }

    public function toSlack($notifiable): SlackMessage
    {
        $url = url("/admin/inquiries/{$this->inquiry->id}");
        $title = match ($this->event) {
            'created'       => '🆕 新規問い合わせ',
            'assigned'      => '👤 担当アサイン',
            'replied'       => '✉️ 返信送信',
            'status_changed'=> '🔄 ステータス変更',
            default         => 'ℹ️ 問い合わせ更新',
        };

        $fields = [
            'Subject' => $this->inquiry->subject,
            'ID'      => (string) $this->inquiry->id,
        ];
        if (isset($this->payload['assignee'])) {
            $fields['Assignee'] = $this->payload['assignee'];
        }
        if (isset($this->payload['old_status'], $this->payload['new_status'])) {
            $fields['Status'] = "{$this->payload['old_status']} → {$this->payload['new_status']}";
        }

        return (new SlackMessage)
            ->from(config('app.name'))
            ->content($title)
            ->attachment(function ($attachment) use ($url, $fields) {
                $attachment->title('管理画面で確認', $url)
                    ->fields($fields);
            });
    }

    // Slack Webhook ルーティング
    public function routeNotificationForSlack($notifiable)
    {
        return config('services.slack.webhook_url');
    }
}