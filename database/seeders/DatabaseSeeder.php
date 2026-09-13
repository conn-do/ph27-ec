<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test',
                'password' => Hash::make('password'),
            ],
        );

        $this->call(OrderSeeder::class);
        $this->call(NewsSeeder::class);
    }
}
