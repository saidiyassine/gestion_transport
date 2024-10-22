<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function authForm(){
        return view("auth");
    }

    public function registrerForm(){
        return view("register");
    }

    public function auth(Request $request){
        $credentials=[
            "email"=>$request->email,
            "password"=>$request->password
        ];
        if(Auth::attempt($credentials)){
            return redirect("admin/dashboard");
            }
        else{
            return redirect("/")->with("error","Adresse e-mail ou mot de passe incorrect");
        }
    }

    public function register(Request $request){
        $user=User::where("email",$request->email)->first();
        if($user){
            return redirect("registerForm")->with('alert', htmlspecialchars_decode("L'adresse e-mail existe déjà."));
        }
        if($request->password !== $request->password_confirmation){
            return redirect("registerForm")->with('alert', htmlspecialchars_decode("Les mots de passe sont différents."));
        }
        if($request->code_enregistrement === "betycor20242025"){
            $user=new User();
            $user->email=$request->email;
            $user->name=$request->name;
            $user->password=Hash::make($request->password);
            $user->save();
            return redirect("/")->with('success', htmlspecialchars_decode("Vous pouvez maintenant vous connecter."));
        }
        else{
            return redirect("registerForm")->with('alert', htmlspecialchars_decode("Le code d'enregistrement est incorrect."));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


}
