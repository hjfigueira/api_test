<?php

namespace Database\Factories;

use App\Models\FundManager;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fund>
 */
class FundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'start_year' => $this->faker->year,
            'fund_manager_id' => function () {
                return FundManager::factory()->create()->id;
            },
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => null,
        ];
    }
}
