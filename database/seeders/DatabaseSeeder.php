<?php

namespace Database\Seeders;

use App\Models\Book;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class
        ]);

        User::factory()->count(20)->create();
        Book::factory()->count(10)->borrowed()->create();
        Book::factory()->count(5)->borrowedAndReturned()->create();
    }
}
