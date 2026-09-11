<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if ($user === null) {
            return;
        }

        $names = ['ロボットふでばこ', 'にじいろマーカー', 'キラキラゲルペン', 'チョコレート'];

        foreach (Product::whereIn('name', $names)->get() as $product) {
            Favorite::updateOrCreate([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }
    }
}
