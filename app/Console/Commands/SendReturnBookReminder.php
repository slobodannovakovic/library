<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SendReturnBookReminderNotification;
use Illuminate\Console\Command;

class SendReturnBookReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-return-book-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a reminder notification to users who borrowed the book 30 days ago.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $users = User::whereHas('notReturnedOnTimeBooks', function ($query) {
            $query->where('borrowed', 1);
        })->with('notReturnedOnTimeBooks')->get();

        if ($users->count() === 0) {
            $this->info('No reminder emails to send.');

            return;
        }

        foreach ($users as $user) {
            foreach ($user->notReturnedOnTimeBooks as $book) {
                $user->notify(new SendReturnBookReminderNotification($book));
                $user->increment('penalties');
            }
        }

        $this->info('Reminder emails sent');
    }
}
