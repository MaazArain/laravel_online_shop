<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // \App\Models\Category::factory(30)->create();
       // Pehle parent tables seed karo
            \App\Models\Category::factory(10)->create();
            \App\Models\SubCategory::factory(20)->create();
            \App\Models\Brand::factory(10)->create();

            // Ab product seed karo (taake foreign keys available ho)
            \App\Models\Product::factory(30)->create();
    }
}
