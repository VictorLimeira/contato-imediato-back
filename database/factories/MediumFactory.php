<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Medium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medium>
 */
class MediumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => Medium::CATEGORIES[array_rand(Medium::CATEGORIES)],
	        'value' => fake()->text(255),
            'contact_id' => Contact::factory()
        ];
    }
}
