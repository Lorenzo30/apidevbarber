<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{  
    public function __construct(){
        $this->middleware('auth:api',["except" => ["create","login","unauthorized"]]);
    }

    public function login (Request $r) {
        $array = ['error' => ''];

        $email = $r->input('email');
        $senha = $r->input('password');

        $token = Auth::attempt(["email" => $email,"password" => $senha]);

        if (!$token) {
            $array['error'] = "Usuario ou senha invalidos";
            return $array;
        }
        
        $info = Auth::user();
        $array['data'] = $info;
        $array['token'] = $token;

        return $array;


    }

    public function create(Request $r){
        $array = ['error' => ''];

        $validator = Validator::make($r->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$validator->fails()) {
            $name = $r->input('name');
            $email = $r->input('email');
            $password = $r->input('password');

            $emailExists = User::where('email',$email)->count();

            if ($emailExists === 0) {
                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = Hash::make($password);
                $newUser->save();

                $token = Auth::attempt(["email" => $email,"password" => $password]);
                if (!$token) {
                    $array['error'] = "Ocorreu um erro";
                    return $array;
                }

                $info = Auth::user();
                $array['data'] = $info;
                $array['token'] = $token;

                return $array;
            } else {
                $array['error'] = "E-mail já cadastrado";
                return $array;
            }

        } else {
            $array['error'] = "Dados incorretos";
            return $array;
        }
    }

    public function logout(){
        Auth::logout();
    }

    public function refresh(){
        $array = ['error' => '']; 
        $token = Auth::refresh();

        $info = Auth::user();
        $array['data'] = $info;
        $array['token'] = $token;

        return $array;
    }

    public function unauthorized() {
        return response()->json(['error' => 'Não autorizado'],401);
    }
}
