<?php

namespace App\Http\Middleware;

use App\Http\Helper\JwtHelper;
use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
//        $data = [
//            'password' => config('settings.jwt.password')
//        ];
//        $jwt = JwtHelper::encodeJwt($data, 36000) ;
//        dd($jwt);
        $password = config('settings.jwt.password');

        $token = JwtHelper::decodeJwt(config('settings.jwt.key'), $request->header('token'));

        if (!$token['result']) {
            return response()->json([__('messages.tokenIsNotValid')])->setStatusCode(403);
        }
        if (!$request->header('token')) {
            return response()->json([__('dict.tokenIsNotValid')])->setStatusCode(403);
        }
        if ($token['body']['body']['password'] !== $password) {
            return response()->json([__('dict.tokenIsNotValid')])->setStatusCode(403);
        }
        return $next($request);
    }

}
