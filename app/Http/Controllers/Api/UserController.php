<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed'
            

        ]);
        $user = new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);

        $user->save();

        return response()->json([
            "status"=>1,
            "msg"=>"registro de usuario exitoso"

        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user=User::where("email",$request->email)->first();

        if(isset($user->id)){
            if(Hash::check($request->password,$user->password))
            {
                //creamos el token
                $token=$user->createToken("auth_token")->plainTextToken;
                //si esta todo ok
                return response()->json([
                    "status"=>1,
                    "msg"=>"Usuario Logueado Exitosamente",
                    "access_token"=>$token
        
                ]);

            }
            else
            {
                return response()->json([
                    "status"=>0,
                    "msg"=>"Password Incorrecto"
        
                ],404);
            }
        }
        else
        {
            return response()->json([
                "status"=>0,
                "msg"=>"Usuario no registrado"
    
            ],404);
        }
        $user = new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);

        $user->save();

        return response()->json([
            "status"=>1,
            "msg"=>"registro de usuario exitoso"

        ]);

    }

    public function userProfile(){
        return response()->json([
            "status"=>0,
            "msg"=>"Perfil de Usuario",
            "data" => auth()->user()

        ]);

    }
    
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status"=>1,
            "msg"=>"Cierre de Sesion"
            

        ]);

    }
}
