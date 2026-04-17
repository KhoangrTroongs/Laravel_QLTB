<?php

namespace App\Notifications;

use App\Models\EquipmentUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowRequestResponse extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public EquipmentUser $borrowRecord)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusText = 'cập nhật';
        if ($this->borrowRecord->status == EquipmentUser::STATUS_BORROWING) {
            $statusText = 'chấp nhận';
        } elseif ($this->borrowRecord->status == EquipmentUser::STATUS_REJECTED) {
            $statusText = 'từ chối';
        } elseif ($this->borrowRecord->status == EquipmentUser::STATUS_RETURNED) {
            $statusText = 'xác nhận hoàn trả';
        }

        return [
            'type' => 'request_response',
            'title' => 'Cập nhật yêu cầu mượn thiết bị',
            'message' => "Yêu cầu mượn thiết bị {$this->borrowRecord->equipment->name} của bạn đã được {$statusText}.",
            'record_id' => $this->borrowRecord->id,
            'link' => route('profile.show'), // User xem trong profile
            'status' => $this->borrowRecord->status,
            'equipment_name' => $this->borrowRecord->equipment->name,
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
