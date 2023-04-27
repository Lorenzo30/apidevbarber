<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Barber;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarberPhotos>
 */
class BarberPhotosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id_barber" =>Barber::all()->random(),
            "url_photo" =>  $this->faker->text(10),
         ];
    }
}
