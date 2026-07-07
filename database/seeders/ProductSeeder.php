<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(){
        $p1 = new Product ();

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->put(
            'images/products/pen.png',
            file_get_contents('public/images/products/pen.png')
        );
        Storage::disk('public')->put(
            'images/products/note.png',
            file_get_contents('public/images/products/note.png')
        );
        Storage::disk('public')->put(
            'images/products/pencil.png',
            file_get_contents('public/images/products/pencil.png')
        );

        $p1 = new Product();

        $p1->name = 'すごいペン';
        $p1->price = fake()->randomNumber(3);
    }
}
};    