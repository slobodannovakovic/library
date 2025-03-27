<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'discription',
        'language',
        'dimensions',
        'borrowed'
    ];

    public const PAGINATION_COUNT = 10;

    public const MAX_DAYS_TO_BORROW = 30;

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('date_borrowed', 'date_returned');
    }

    public function isBorrowed(): bool
    {
        return $this->borrowed !== 0;
    }

    public function scopeAvailable($query)
    {
        return $query->where('borrowed', 0);
    }
}
