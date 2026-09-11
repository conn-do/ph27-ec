<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
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

        $penCategory = Category::where('slug', 'pen')->first();

        $p1 = new Product;
        $p1->name = 'すごいペン';
        $p1->price = fake()->randomNumber(3);
        $p1->description = 'とてもすごいペンです。';
        $p1->image = 'images/products/pen.png';
        $p1->category_id = $penCategory->id;
        $p1->save();

        $p2 = new Product;
        $p2->name = 'きれいなノート';
        $p2->price = fake()->randomNumber(3);
        $p2->description = 'とてもきれいなノートです。';
        $p2->image = 'images/products/note.png';
        $p2->category_id = $penCategory->id;
        $p2->save();

        $p3 = new Product;
        $p3->name = 'よく消える鉛筆';
        $p3->price = fake()->randomNumber(3);
        $p3->description = 'とてもよく消える鉛筆です。';
        $p3->image = 'images/products/pencil.png';
        $p3->category_id = $penCategory->id;
        $p3->save();

        $additionalProducts = [
            ['name' => 'MONO消しゴム', 'price' => 120, 'stock' => 30, 'description' => '消し味なめらかな定番の消しゴムです。', 'image' => 'images/products/eraser.jpg', 'category_id' => $penCategory->id],
        ];

        foreach ($additionalProducts as $data) {
            Storage::disk('public')->put(
                $data['image'],
                file_get_contents('public/'.$data['image'])
            );

            $product = new Product;
            $product->name = $data['name'];
            $product->price = $data['price'];
            $product->stock = $data['stock'];
            $product->description = $data['description'];
            $product->image = $data['image'];
            $product->category_id = $data['category_id'];
            $product->save();
        }
    }
}
