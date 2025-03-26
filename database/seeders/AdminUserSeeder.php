<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Miroslav Ilić',
            'email' => 'miroslav@mrcajevci.rs',
            'role_id' => 1
        ]);

        User::factory()->create([
            'name' => 'Veliki Štrumpf',
            'email' => 'veliki@strumpf.rs',
            'role_id' => 1
        ]);
    }
}
