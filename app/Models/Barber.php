<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\BarberPhotos;
use App\Models\BarberServices;
use App\Models\BarberTestimonial;
use App\Models\BarberAvailabity;

class Barber extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function photos(){
        return $this->hasMany(BarberPhotos::class,"id_barber");
    }

    public function services(){
        return $this->hasMany(BarberServices::class,"id_barber");
    }

    public function testimonial(){
        return $this->hasMany(BarberTestimonial::class,"id_barber")->select(["name"]);
    }

    public function avilabity (){
        return $this->hasMany(BarberAvailabity::class,"id_barber");
    }
}
