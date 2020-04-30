<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Users;
use Firebase\JWT\JWT;

class Auth extends Controller
{
    public function loginView()
    {
        return view('auth/login');
    }

    public function web_login(Request $req)
    {
        $req->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $req->merge(['password' => hash("sha256", $req->password)]);
        $user = Users::where('email', $req->email)->where('password', $req->password)->first();
        if ($user == null) {
            $user = Users::where('username', $req->email)->where('password', $req->password)->first();
        }
        if ($user == null) {
            return response()->json(['message'=>'user not found'], 404);
        }

        /**
         * Generate JWT
         * Generate JWT and store to cookie
         */
        $time = time();
        
        // time()+(second*n)
        $payload_at = array(
            'iss' => 'http://127.0.0.1:8000',
            'aud' => 'http://127.0.0.1:8000',
            'iat' => $time,
            'exp' => $time+(60*5),
            'data' => array(
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username
            )
        );
        $payload_rt = array(
            'iss' => 'http://127.0.0.1:8000',
            'aud' => 'http://127.0.0.1:8000',
            'iat' => $time,
            'exp' => $time+(3600*24),
            'data' => array(
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username
            )
        );
        $at = JWT::encode($payload_at, env('APP_KEY'));
        $rt = JWT::encode($payload_rt, env('APP_KEY'));
        
        setcookie("at", $at, [
            'expires'=>$time+(60*24),
            'path'=>'/',
            'httponly'=>false,
            'samesite'=>'Strict'
        ]);
        setcookie("rt", $rt, [
            'expires'=>$time+(3600*24),
            'path'=>'/',
            'httponly'=>false,
            'samesite'=>'Strict'
        ]);
        /**
         * End Generate JWT
         */
        
        return response()->json(['mesage'=>'authorized']);
    }

    public function registerView()
    {
        return view('auth/register');
    }

    public function register(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
        ]);

        $req->merge(['password' => hash("sha256", $req->password)]);
        Users::create($req->input());

        return response()->json(["message"=>"please login!"]);
    }

    public function logout()
    {
        setcookie("at", null, [
            'expires'=>0,
            'path'=>'/',
            'httponly'=>false,
            'samesite'=>'Strict'
        ]);
        setcookie("rt", null, [
            'expires'=>0,
            'path'=>'/',
            'httponly'=>false,
            'samesite'=>'Strict'
        ]);

        return redirect()->route('login');
    }
}
