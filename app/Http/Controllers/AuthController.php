<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function register (Request $request) {

        $fields = $request->validate([
            'name' => 'required|string' ,
            'email' => 'required|string|unique:users,email' ,
            'password' => 'required|confirmed' ,
        ]) ;

        $user = User::create([
            'name' => $fields['name'] ,
            'profile_id' => 1 ,
            'email' => $fields['email'] ,
            'password' => bcrypt($fields['password']) ,
        ]) ;

        $token = $user->createToken('myapptoken')->plainTextToken ;

        $response = [
            'user' => $user,
            'token'=> $token
        ] ;
        return response($response, 201) ;
    }

    public function login (Request $request) {

        $fields = $request->validate([
            'email' => 'required|string' ,
            'tokennotifs' => 'string' ,
            'password' => 'required' ,
        ]);

        $user_connected = Auth::user();
        $user = User::where('email', $fields['email'])->first() ;

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401) ;
        }

       // $token = $user->createToken('myapptoken')->plainTextToken ;
        $token = csrf_token() ;
       // dd($token);
        $user->token = $token ;
        $user->tokennotifs = $fields['tokennotifs'] ;
        $user->save() ;
        $response = [
            'user' => $user
        ] ;
        return response($user, 201) ;
    }

    public function tokeninsert (Request $request) {

        $fields = $request->validate([
            'id' => 'string' ,
            'tokennotifs' => 'string' ,
        ]) ;

        //dd($fields) ;
        $user = User::find(intval($fields['id'])) ;

        $user->tokennotifs = $fields['tokennotifs'] ;
        $user->save() ;
        $response = [
            'user' => $user
        ] ;
        return response($user, 201) ;
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete() ;

        return [
            'message' => 'Logged out'
        ] ;
    }
}
