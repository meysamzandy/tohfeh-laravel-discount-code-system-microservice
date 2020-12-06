<?php

namespace App\Http\Middleware;

use App\Http\Helper\JwtHelper;
use Closure;
use Illuminate\Http\Request;

class MassiveToken
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
//            'password' => config('settings.massive_jwt.password')
//        ];
//        $jwt = JwtHelper::encodeJwt('HS512',config('settings.massive_jwt.key'),$data, 360000) ;
//        dd($jwt);
        $password = config('settings.massive_jwt.password');

        $token = JwtHelper::decodeJwt('HS512',config('settings.massive_jwt.key'), $request->header('token'));
        if (!$request->header('token')) {
            return response()->json([__('messages.tokenIsNotValid')])->setStatusCode(403);
        }
        if (!$token['result_status']) {
            return response()->json([$token['result']])->setStatusCode(403);
        }
        if ($token['result']['body']['password'] !== $password) {
            return response()->json([__('messages.tokenIsNotValid')])->setStatusCode(403);
        }
        return $next($request);
    }

}
