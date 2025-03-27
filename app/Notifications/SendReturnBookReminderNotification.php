<?php

namespace App\Notifications;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendReturnBookReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Book $book)
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $dateBorrowed = Carbon::parse($this->book->pivot?->date_borrowed)
            ->addDays(Book::MAX_DAYS_TO_BORROW)->format('d.m.Y');

        return (new MailMessage)
            ->line('Please return the book you borrowed.')
            ->line("Book name: {$this->book->title}")
            ->line("Book author: {$this->book->author}")
            ->line("Should have been returned by: $dateBorrowed");
    }
}
