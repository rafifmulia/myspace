<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;

class AuthJwt
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
        // check if there bearer token
        try {
            $auth = $request->header()['authorization'];
        } catch (\Exception $e) {
            return response()->json(['error'=>'Not Bearer Token']);
        }
        
        // get bearer token
        if (preg_match('/Bearer\s(\S+)/', $auth[0], $matches)) {
            $is_web = false;

            // if there cookies 'rt' => generate access_token from refresh_token, if not return error
            if (!isset($_COOKIE['rt'])) {
                $is_web = false;
            } else {
                $is_web = true;
            }

            // get access_token from bearer, if cannot be decoded, return error
            if (!$is_web) {
                try {
                    $at = JWT::decode($matches[1], env('APP_KEY'), array('HS256'));
                    $request->jwt = $at->data;
                } catch(\Exception $e) {
                    return response()->json(['error'=>'Token Not Valid or Expired']);
                }
            } else {
                // get cookies 'rt' => generate access_token from refresh_token, if not redirect to login
                try {
                    $rt = JWT::decode($_COOKIE['rt'], env('APP_KEY'), array('HS256'));
                    $time = time();

                    $rt->iat = $time;
                    $rt->exp = $time+(60*1);

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
            
        }

        $response = $next($request);
        return $response;
    }
}
