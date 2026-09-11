<?php

namespace Database\Seeders;

use App\Models\AllowanceTransaction;
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
        $this->seedUsers();

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            NewsSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            FavoriteSeeder::class,
        ]);
    }

    /**
     * かんりにん 1にんと、こどもの アカウント 2にん。
     */
    private function seedUsers(): void
    {
        $users = [
            ['name' => 'Test', 'email' => 'test@example.com', 'allowance_balance' => 3000],
            ['name' => 'ひかる', 'email' => 'hikaru@example.com', 'allowance_balance' => 1500],
            ['name' => 'ななみ', 'email' => 'nanami@example.com', 'allowance_balance' => 800],
        ];

        foreach ($users as $row) {
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );

            $user->forceFill(['allowance_balance' => $row['allowance_balance']])->save();

            $grant = AllowanceTransaction::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'reason' => AllowanceTransaction::REASON_ALLOWANCE,
                ],
                [
                    'amount' => $row['allowance_balance'],
                    'balance_after' => $row['allowance_balance'],
                ],
            );

            // おこづかい帳が じかんじゅんに ならぶよう、さいしょの きろくは 1かげつ前にする。
            $grant->forceFill([
                'created_at' => now()->subMonth(),
                'updated_at' => now()->subMonth(),
            ])->save();
        }
    }
}
