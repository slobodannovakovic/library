<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dimensions = [
            '12.7 x 20.32 cm', 
            '13.97 x 21.59 cm', 
            '15.24 x 22.86 cm'
        ];

        return [
            'title' => fake()->sentence(4, true),
            'author' => fake()->name(),
            'description' => fake()->paragraph(3),
            'language' => array_rand(['English', 'Serbian', 'Franch']),
            'dimensions' => $dimensions[array_rand($dimensions)],
            'borrowed' => rand(0,1),
        ];
    }

    public function borrowed()
    {
        return $this->afterCreating(function (Book $book) {
            if (! $book->isBorrowed()) {
                return;
            }

            $user = User::inRandomOrder()->first() ?? User::factory()->create();
            
            $book->users()->attach($user->id, [
                'date_borrowed' => fake()->dateTimeBetween('-5 week'),
                'date_returned' => null,
            ]);
        });
    }

    public function borrowedAndReturned()
    {
        return $this->afterCreating(function (Book $book) {
            if (! $book->isBorrowed()) {
                return;
            }

            $user = User::inRandomOrder()->first() ?? User::factory()->create();
            
            $book->users()->attach($user->id, [
                'date_borrowed' => fake()->dateTimeBetween('-3 week'),
                'date_returned' => fake()->dateTimeBetween('-1 week'),
            ]);

            $book->update(['borrowed' => 0]);
        });
    }
}
