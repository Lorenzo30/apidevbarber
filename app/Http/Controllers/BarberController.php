<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barber;
use App\Models\BarberServices;
use App\Models\BarberAvailabity;
use App\Models\UsersAppoiment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarberController extends Controller
{
    public function __construct(){
        $this->middleware("auth:api");
        $this->loggedUser  = auth()->user();
    }

    public function list(Request $r){
        $array = ['error' => ''];
        $offset = $r->input("offset");
        if(!$offset){
            $offset = 0;
        }
        $array['data'] =  DB::table('barbers')->select("stars")->where("stars",">",1.5)->get();
        $array['loc'] = "São paulo";
        return $array;
    }

    public function one($id){
        $array = ["error" => ''];
        $barber = Barber::find(20);
        if ($barber) {
           $array["favorited"] = [];
           $array["services"] = [];
           $array["testimonials"] = [];
           $array["available"] = [];

           $array['photos'] = $barber->photos;
           $array['services'] = $barber->services;
           $array['testimonial'] = $barber->testimonial;

           $avilabity = [];

           $avails = $barber->avilabity;
           $availWeekday = [];
           foreach($avails as $item){
                $availWeekday[$item['weekday']] = explode(",",$item['hours']);
           }
          
           $appQuery = UsersAppoiment::where("id_barber",$id)
           ->whereBetween("ap_datetime",[
                Carbon::today()->startOfDay(),
                Carbon::today()->addDays(20)->endOfDay()
           ])->get();

           foreach($appQuery as $appItem){
                $appoiments[] = $appItem['ap_datetime'];
           }

           for ($q=0;$q<20;$q++){
                $timeItem =  Carbon::now()->addDays($q);
                $weekday = $timeItem->dayOfWeek;
                
               
                if(in_array($weekday,array_keys($availWeekday))){
                    $horasDoDiaDisponivel = $availWeekday[$weekday];// 1: 10:00,12:00
                    $hours = [];
                    foreach($horasDoDiaDisponivel as $hora){
                        $dataAVerificar = $timeItem->setTimeFromTimeString($hora)->format("Y-m-d H:i:s");
                        if(!in_array($dataAVerificar,array_values($appoiments))){
                            $hours[] = $hora;
                        }
                    }
                    
                    if(count($hours) > 0){
                        $avilabity[] = [
                          "day" => $dataAVerificar,
                          "hours" => implode(",",$hours)
                        ];
                       
                    }
                   
                }
           }

           $appoiments = [];


           $array['avaliabity'] = $avilabity;
           return $array;
        } else {
            $array['error'] = "Barbeiro não existe";
            return $array;
        }
    }

    public function search(Request $r){
        $array = ['error' => '','list' => []];
        $q = $r->input('q');

        if ($q) {
            $barbers = Barber::select()->where('name','LIKE','%'.$q.'%')->get();
            $array['list'] = $barbers;
        } else {
            $array['error'] = "Nenhum nome informado";
        }
        return $array;
    }

    public function setAppointment($id,Request $r){
        $array = ['error' => []];

        $service = $r->input("service");
        $year = intval($r->input("year"));
        $month = intval($r->input("month"));
        $day = intval($r->input("day"));
        $hour = intval($r->input("hour"));

        $month = $month < 10 ? "0".$month : $month;
        $year = $year < 10 ? "0".$year : $year;
        $day = $day < 10 ? "0".$day : $day;
        $hour = $hour < 10 ? "0".$hour : $hour;

        $barberService = BarberServices::select()->where("id",$service)->first();
        if ($barberService) {
            $apDate = $year."-".$month."-".$day." ".$hour.":00:00";
            if (strtotime($apDate)) {
                $apps  = UsersAppoiment::select()->where("id_barber",$id)->where("ap_datetime",$apDate)->count();
                if($apps == 0){
                    $weekday = date("w",strtotime($apDate));
                    $avail = BarberAvailabity::select()->where("id_barber",$id)->where("weekday",$weekday)->first();
                    if ($avail) {
                        $hours = explode(",",$avail['hours']);
                        if (in_array($hour.":00",$hours)) {
                            $newApp = new UsersAppoiment;
                            $newApp->id_user = $this->loggedUser->id;
                            $newApp->id_barber = $id;
                            $newApp->ap_datetime = $apDate;
                            $newApp->id_service = $service;
                            $newApp->save();
                        } else {
                            $array['error'] = "Não atende nessa hora";
                        }
                    } else {
                        $array['error'] = "Barbeiro não atende nesse dia";
                    }
                } else {
                    $array['error'] = "Já possoui já agendamento";
                }
            } else {
                $array['error'] = "Data invalida";
            }
        } else {
            $array['error'] = "Serviço inexistente";
        }

        return $array;
    }
}
