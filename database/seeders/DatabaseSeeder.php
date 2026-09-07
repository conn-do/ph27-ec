<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(ChirpSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);

        User::firstOrCreate(['email' => 'test@example.com'], [
            'name' => 'Test',
            'password' => Hash::make('password'),
        ]);

        $this->call(OrderSeeder::class);

        $this->call([
            NewsSeeder::class,
        ]);
    }
}
