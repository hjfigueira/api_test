<?php

namespace Database\Factories;

use App\Models\Fund;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FundDuplicatesCadidate>
 */
class FundDuplicatesCandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resolved'      => false,
            'created_at'    => $this->faker->dateTime(),
            'updated_at'    => $this->faker->dateTime(),
            'parent_id'     => function(){
                return Fund::factory()->create()->id;
            },
            'duplicate_id'  => function(){
                return Fund::factory()->create()->id;
            },
        ];
    }
}
