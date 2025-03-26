<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('date_borrowed', 'date_returned');
    }

    public function isBorrowed(): bool
    {
        return $this->borrowed !== 0;
    }
}
