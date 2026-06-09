<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(){
        $p1 = new Product ();
        $p1->name = 'すごいペン';
        $p1->price = fake()->randomNumber(3);
