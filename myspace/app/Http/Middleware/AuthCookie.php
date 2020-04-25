<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cookie;

class AuthCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $at_expired = false;

        // Get Data from access_token
        try {
            $at = JWT::decode($_COOKIE['at'], env('APP_KEY'), array('HS256'));
            $request->jwt = $at->data;
        } catch(\Exception $e) {
            $at_expired = true;
        }

        // Try to generate access_token from refresh_token
        if ($at_expired == true) {
            try {
                $rt = JWT::decode($_COOKIE['rt'], env('APP_KEY'), array('HS256'));
                $time = time();

                $rt->iat = $time;
                $rt->exp = $time+(60*5);

                $at = JWT::encode($rt, env('APP_KEY'));

                setcookie("at", $at, [
                    'expires'=>$time+(60*24),
                    'path'=>'/',
                    'httponly'=>false,
                    'samesite'=>'Strict'
                ]);

                $request->jwt = $rt->data;
            } catch (\Exception $e) {
                return redirect()->route('login');
            }
        }

        $response = $next($request);
        return $response;
    }
}
