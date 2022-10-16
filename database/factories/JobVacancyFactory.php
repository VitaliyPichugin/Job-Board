<?php declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class JobVacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = \App\Models\User::factory(15)->create();

        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->text(80),
            'user_id' => $user->random(),
        ];
    }
}
