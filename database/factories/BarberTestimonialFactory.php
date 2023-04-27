<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Barber;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BarberTestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           "id_barber" => Barber::all()->random(),
           "name" =>  $this->faker->text(10),
           "rate" => $this->faker->randomFloat(1, 0, 5),
           "body" => $this->faker->text(20)
        ];
    }
}
