<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'slug' => $this->faker->slug,
            // 'description' => $this->faker->text,
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->numberBetween(100, 1000),
            'category_id' => $this->faker->numberBetween(1, 6),
            'sub_category_id' => $this->faker->numberBetween(1, 6),
            'default_image' => $this->faker->imageUrl(),

        ];
    }
}
