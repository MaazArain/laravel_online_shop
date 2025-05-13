<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);
        $slug = Str::slug($title);
    
        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'sub_category_id' => SubCategory::inRandomOrder()->first()?->id ?? 1,
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? 1,
            'price' => fake()->numberBetween(100, 1000),
            'sku' => fake()->unique()->numerify('SKU###'),
            'track_qty' => 'Yes',
            'qty' => 10,
            'is_featured' => 'Yes',
            'status' => 1,
        ];
    }
}
