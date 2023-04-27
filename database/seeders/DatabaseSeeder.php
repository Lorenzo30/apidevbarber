<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        \App\Models\Barber::factory(10)->create();
        \App\Models\UserFavorite::factory(10)->create();
        \App\Models\UsersAppoiment::factory(10)->create();
        \App\Models\BarberTestimonial::factory(10)->create();
        \App\Models\BarberServices::factory(10)->create();
        \App\Models\BarberReviews::factory(10)->create();
        \App\Models\BarberPhotos::factory(10)->create();
        \App\Models\BarberAvailabity::factory(10)->create();

    }
}
