<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Barber;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarberAvailabity>
 */
class BarberAvailabityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_barber' => Barber::all()->random(),
            "weekday" => $this->faker->numberBetween(0, 4),
            "hours" => "10:00"
        ];
    }
}
