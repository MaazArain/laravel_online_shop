<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategory>
 */
class SubCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();
        return [
            'name' => $name, // ✅ Match with DB and model
            'slug' => Str::slug($name),
            'status' => 1,
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'showHome' => 'Yes',
        ];
    }
}
