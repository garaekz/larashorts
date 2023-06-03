<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $urlable = $this->urlable();

        return [
            'original_url' => $this->faker->url,
            'code' => $this->faker->unique()->word(),
            'urlable_type' => $urlable,
            'urlable_id' => $urlable::factory(),
        ];
    }

    public function urlable()
    {
        return $this->faker->randomElement([
            User::class,
            Application::class,
        ]);
    }
}
