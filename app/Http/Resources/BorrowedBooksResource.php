<?php

namespace App\Http\Resources;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowedBooksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dateBorrowed = Carbon::parse($this->pivot->date_borrowed)
            ->format('d.m.Y');
        $dateToReturn = Carbon::parse($this->pivot->date_borrowed)
            ->addDays(Book::MAX_DAYS_TO_BORROW)->format('d.m.Y');

        return [
            'title' => $this->title,
            'author' => $this->author,
            'language' => $this->language,
            'dateBorrowed' => $dateBorrowed,
            'dateToReturn' => $dateToReturn
        ];
    }
}
