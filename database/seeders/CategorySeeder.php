<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Fashion & Clothing',
            'Electronics',
            'Home & Living',
            'Health & Beauty',
            'Groceries',
            'Sports & Outdoors',
            'Books & Stationery',
            'Other',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}