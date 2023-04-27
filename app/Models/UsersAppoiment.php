<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarberServices;
use App\Models\Barber;
class UsersAppoiment extends Model
{
    use HasFactory;

    protected $table = "usersappoiments";
    public $timestamps = false;

    public function service(){
        return $this->hasOne(BarberServices::class,"id","id_service");
    }

    public function barber(){
        return $this->hasOne(Barber::class,"id","id_barber");
    }

    public function user(){
        
    }
}
