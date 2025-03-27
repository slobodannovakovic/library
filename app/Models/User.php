<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'penalties'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const ADMIN_ROLE_NAME = 'admin';

    public const PENALTIES_MAX = 10;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class)
                    ->withPivot('date_borrowed', 'date_returned');
    }

    public function notReturnedOnTimeBooks()
    {
        return $this->belongsToMany(Book::class)
                    ->wherePivot('date_borrowed', '<', now()->subDays(Book::MAX_DAYS_TO_BORROW))//Book::MAX_DAYS_TO_BORROW
                    ->wherePivotNull('date_returned')
                    ->withPivot('date_borrowed', 'date_returned');
    }
}
