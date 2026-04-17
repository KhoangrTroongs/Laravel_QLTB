<?php

namespace App\Console\Commands;

use App\Models\EquipmentUser;
use App\Notifications\OverdueReminderNotification;
use Illuminate\Console\Command;

class NotifyOverdueBorrowings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-overdue-borrowings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to users who have overdue equipment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $overdueRecords = EquipmentUser::where('status', EquipmentUser::STATUS_BORROWING)
            ->where('hantra', '<', now())
            ->whereDoesntHave('user', function ($q) {
                $q->whereNull('id'); // Ensure user exists
            })
            ->get();

        $count = 0;
        foreach ($overdueRecords as $record) {
            // Kiểm tra xem đã gửi thông báo quá hạn hôm nay chưa (tránh spam)
            $alreadyNotifiedToday = $record->user->notifications()
                ->where('data->type', 'overdue_reminder')
                ->where('data->record_id', $record->id)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if (! $alreadyNotifiedToday) {
                $record->user->notify(new OverdueReminderNotification($record));
                $count++;
            }
        }

        $this->info("Đã gửi {$count} thông báo quá hạn.");
    }
}
