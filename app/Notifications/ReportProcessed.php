<?php

namespace App\Notifications;

use App\Http\Resources\Client\Notification\PostNotificationResource;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportProcessed extends Notification
{
    use Queueable;

    public Report $report;
    public int|null $totalBanDays;
    public bool $warn;
    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report, bool $warn, null|int $totalBanDays)
    {
        $this->report = $report;
        $this->warn = $warn;
        $this->totalBanDays = $totalBanDays;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'userName' => $this->report->sender->getFullName(),
            'reportReasonType' => $this->report->reason->name,
            'object' => $this->report->object,
            'post' => $this->report->post !==null ? new PostNotificationResource($this->report->post) : null,
            'topic' => $this->report->topic,
            'author' => [
                'id' => $this->report->userId,
                'name' => $this->report->user->getFullName(),
            ],
            'warn' => $this->warn,
            'totalBanDays' => $this->totalBanDays,
        ];
    }
}
