<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Users;
use Firebase\JWT\JWT;

class Auth extends Controller
{

    public function ponies()
    {

    }

    public function login(Request $req)
    {
        $req->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $req->merge(['password' => hash("sha256", $req->password)]);

        // login dapat menggunakan email / username
        if ($req->has('username')) {
            $user = Users::where('username', $req->username)->where('password', $req->password)->first();
        }
        $user = Users::where('email', $req->email)->where('password', $req->password)->first();
        
        if ($user == null) {
            return response()->json(['message'=>'user not found'], 404);
        }

        /**
         * Generate JWT
         */
        $time = time();
        
        // time()+(3600*2) => time()+(second*n)
        $payload_access_token = array(
            'iss' => 'http://127.0.0.1:8000',
            'aud' => 'http://127.0.0.1:8000',
            'iat' => $time,
            'exp' => $time+(3600*2),
            'data' => array(
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username
            )
        );
        $access_token = JWT::encode($payload_access_token, env('APP_KEY'));
        $payload_refresh_token = array(
            'iss' => 'http://127.0.0.1:8000',
            'aud' => 'http://127.0.0.1:8000',
            'iat' => $time,
            'exp' => $time+(3600*24),
            'userid' => $user->id
        );
        $refresh_token = JWT::encode($payload_refresh_token, env('APP_KEY'));
        /**
         * End Generate JWT
         */
        
        return response()->json(['access_token'=>$access_token,'refresh_token'=>$refresh_token]);
    }

    public function refresh(Request $req)
    {
        try {
            $decode = JWT::decode($req->refresh_token, env('APP_KEY'), array('HS256'));
        } catch (\Exception $e) {
            return response()->json(['error'=>'Refresh Token Not Valid or Expired']);
        }

        if (!isset($decode->userid)) {
            return response()->json(['error'=>'User not found'], 400);
        }

        $user = Users::where('id', $decode->userid)->first();
        $time = time();

        $payload = array(
            'iss' => 'http://127.0.0.1:8000',
            'aud' => 'http://127.0.0.1:8000',
            'iat' => $time,
            'exp' => $time+(3600*2),
            'data' => array(
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username
            )
        );
        $jwt = JWT::encode($payload, env('APP_KEY'));

        return response()->json(['access_token'=>$jwt]);
    }
}
