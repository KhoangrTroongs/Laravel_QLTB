<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MissedBorrowRequest extends Notification
{
    use Queueable;

    public function __construct(public $borrowRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'missed_request',
            'title' => 'Đã lỡ (Missed) đơn mượn',
            'message' => 'Đơn mượn thiết bị "' . $this->borrowRequest->equipment->name . '" của nhân viên ' . $this->borrowRequest->user->name . ' đã bị lỡ do quá hạn duyệt.',
            'request_id' => $this->borrowRequest->id,
            'equipment_name' => $this->borrowRequest->equipment->name,
            'user_name' => $this->borrowRequest->user->name,
        ];
    }
}
