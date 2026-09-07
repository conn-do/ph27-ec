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
            file_get_contents(public_path('images/products/pen.png'))
        );
        Storage::disk('public')->put(
            'images/products/note.png',
            file_get_contents(public_path('images/products/note.png'))
        );
        Storage::disk('public')->put(
            'images/products/pencil.png',
            file_get_contents(public_path('images/products/pencil.png'))
        );

        $category = Category::where('slug', 'pen')->first();

        $p1 = Product::firstOrNew(['name' => 'すごいペン']);
        $p1->name = 'すごいペン';
        $p1->price = 380;
        $p1->stock = 30;
        $p1->description = 'とてもすごいペンです。';
        $p1->image = 'images/products/pen.png';
        $p1->category_id = $category->id;
        $p1->save();

        $p2 = Product::firstOrNew(['name' => 'きれいなノート']);
        $p2->name = 'きれいなノート';
        $p2->price = 680;
        $p2->stock = 30;
        $p2->description = 'とてもきれいなノートです。';
        $p2->image = 'images/products/note.png';
        $p2->category_id = Category::where('slug', 'notebook')->firstOrFail()->id;
        $p2->save();

        $p3 = Product::firstOrNew(['name' => 'よく消える鉛筆']);
        $p3->name = 'よく消える鉛筆';
        $p3->price = 180;
        $p3->stock = 30;
        $p3->description = 'とてもよく消える鉛筆です。';
        $p3->image = 'images/products/pencil.png';
        $p3->category_id = $category->id;
        $p3->save();
    }
}
