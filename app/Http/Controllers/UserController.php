<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\UserFavorite;
use App\Models\User;

use Intervention\Image\Facades\Image;

class UserController extends Controller
{   
    private $loggedUser;
    public function __construct(){
        $this->middleware("auth:api");
        $this->loggedUser  = auth()->user();
    }

    public function read(){
        $array = ['error' => ''];
        $info = $this->loggedUser;
        $array['data'] = $info;

        return $array;
    }

    public function getFavorites(){
        $array = ['error' => ""];
        $favorites = $this->loggedUser->favorites;
        $array['favorites'] = $favorites;
        return $array;
    }

    public function getAppointments(){
        $array = ['error' => ''];
        $apoiments = $this->loggedUser->appoiments;

        foreach($apoiments as $ap){
            $service = $ap->service;
            $barber = $ap->barber;
            $list = [
                "data" => date("d/m/Y H:i:s",strtotime($ap->ap_datetime)),
                "barbeiro" => $barber->name,
                "serviço" => $service->name,
                "preço" => $service->price
            ];
            $array['list'][] = $list;
        }

        return $array;
       
    }

    public function addFavorite(Request $r){
        $array = ['error' => ''];
        $barber = $r->input('barber');
        if ($barber) {
            $hasFavorite = $this->loggedUser->favorite($barber);
            if(!$hasFavorite){
                $newFav = new UserFavorite();
                $newFav->id_user =  $this->loggedUser->id;
                $newFav->id_barber = $barber;
                $newFav->save();
                $array['hav'] = true;
            } else {
                $fav = UserFavorite::select()->where("id_user",$this->loggedUser->id)->where("id_barber",$barber)->first();
                $fav->delete();
                $array['hav'] = false;
            }
        } else {
            $array['error'] = "precisa enviar um barbeiro";
        }

        return $array;
    }

    public function update(Request $r){
        $array = ['error' => ''];
        $rules = [
            "name" => 'min:2',
            "email" => 'email|unique:users',
            "password" => 'same:password_confirm',
            "password_confirm" => 'same:password'
        ];

        $validator = Validator::make($r->all(),$rules);
       
        if ($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        } else {
            $name = $r->input("name");
            $email = $r->input("email");
            $password = $r->input("password");
            $password_confirm = $r->input("password_confirm");

            $user = User::find($this->loggedUser->id);
            if ($name) {
                $user->name = $name;
            }
            
            if ($email) {
                $user->email = $email;
            }

            if ($password) {
                $user->password = password_hash($password);
            }
            
            $user->save();
        }
        return $array;
    }

    public function updateAvatar(Request $r){
        $array = ['error'];
        $rules = [
            'avatar' => 'required|image|mimes:png,jpg,jpeg'
        ];

        $validator = Validator::make($r->all(),$rules);
        if($validator->fails()){
            $array['error'] = $validator->messages();
        }
        
        $avatar = $r->file('avatar');

        $dest = public_path('/media/avatars');
        $avatarName = md5(time().rand()).'.jpg';

        $img = Image::make($avatar->getRealPath());
        $img->fit(300,300)->save($dest.'/'.$avatarName);

        $user = User::find($this->loggedUser->id);
        $user->avatar = $avatarName;
        $user->save();


        return $array;
    }
}
