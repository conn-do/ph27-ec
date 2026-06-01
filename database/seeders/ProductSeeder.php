<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $p1 = new Product();
        $p1->name = 'すごいペン';
        $p1->price = fake()->randomNumber(3);
        $p1->description = 'とてもすごいペンです。';
        $p1->save();

        $p2 = new Product();
        $p2->name = 'きれいなノート';
        $p2->price = fake()->randomNumber(3);
        $p2->description = 'とてもきれいなノートです。';
        $p2->save();

        $p3 = new Product();
        $p3->name = 'よく消える消しゴム';
        $p3->price = fake()->randomNumber(3);
        $p3->description = 'とてもよく消える消しゴムです。';
        $p3->save();
    }
}
