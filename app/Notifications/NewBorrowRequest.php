<?php

namespace App\Notifications;

use App\Models\EquipmentUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBorrowRequest extends Notification
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
        return [
            'type' => 'new_request',
            'title' => 'Yêu cầu mượn thiết bị mới',
            'message' => "Nhân viên {$this->borrowRecord->user->name} đã gửi yêu cầu mượn {$this->borrowRecord->equipment->name}",
            'record_id' => $this->borrowRecord->id,
            'link' => route('equipment-users.show', $this->borrowRecord->id),
            'user_name' => $this->borrowRecord->user->name,
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
