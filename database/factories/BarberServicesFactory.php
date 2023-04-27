<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Barber;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarberServices>
 */
class BarberServicesFactory extends Factory
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
            "price" => $this->faker->randomFloat(2, 0, 100)
         ];
    }
}
