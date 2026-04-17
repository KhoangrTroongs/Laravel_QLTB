<?php

namespace App\Notifications;

use App\Models\EquipmentUser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueReminderNotification extends Notification
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
            'type' => 'overdue_reminder',
            'title' => 'Cảnh báo quá hạn trả thiết bị',
            'message' => "Thiết bị {$this->borrowRecord->equipment->name} của bạn đã quá hạn trả vào ngày ".Carbon::parse($this->borrowRecord->hantra)->format('d/m/Y').'. Vui lòng hoàn trả sớm.',
            'record_id' => $this->borrowRecord->id,
            'link' => route('profile.show'),
            'deadline' => $this->borrowRecord->hantra,
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
