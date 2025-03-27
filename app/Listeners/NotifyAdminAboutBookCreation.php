<?php

namespace App\Listeners;

use App\Events\BookCreatedEvent;
use App\Models\User;
use App\Notifications\BookCreationAdminNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminAboutBookCreation
{
    /**
     * Create the event listener.
     */
    public function __construct(private Request $request)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookCreatedEvent $event): void
    {
        $admins = User::where('role_id', 1)
            ->where('id', '!=', $this->request->user()->id)
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new BookCreationAdminNotification($event->book));
        }
    }
}
