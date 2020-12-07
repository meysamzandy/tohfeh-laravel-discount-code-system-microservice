<?php

namespace App\Http\Controllers;

use App\Http\Helper\JwtHelper;
use App\Http\Helper\SmallHelper;
use App\Http\Helper\ValidatorHelper;
use App\Models\DiscountCode;
use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nowakowskir\JWT\Base64Url;

class ProcessCodeForAuthenticatedUser extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';

    public function code(Request $request, DiscountCode $discountCode)
    {
//        $data = [
//              "auid" =>  "3f577010-a879-4291-9968-b5c9f1622846",
//              "bool" =>  "100000",
//              "code" =>  "98",
//              "cp" =>  null,
//              "dc" =>  null,
//              "dl1" =>  0,
//              "dl2" =>  0,
//              "exp" =>  1606284992,
//              "jti" =>  "2ffc88df-e467-4326-a656-07a62b55e896",
//              "ls" =>  "1",
//              "name" =>  "کاربر فیلمگردی",
//              "phone" =>  "9034216449",
//              "rct" =>  null,
//              "rn" =>  null,
//              "sb" =>  0,
//              "sp" =>  null,
//              "st" =>  0,
//              "suid" =>  "ae83ee31-70fb-40d7-9c0b-766199ce832d"
//        ];
//        $jwt = JwtHelper::encodeJwt('HS256',config('settings.user_management_jwt.key'), $data, 36000);
//        dd($jwt);



        if (!$request->bearerToken()) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.tokenIsNotNotExist
            ')])->setStatusCode(400);
        }
        // validate market data
        $validator = (new ValidatorHelper)->marketValidator($request->post());
        if ($validator->fails()) {
            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(400);
        }

        $tokenData = SmallHelper::getPayloadFromJwt($request->bearerToken());
        // check if token is invalid
        if (!$tokenData) {
            return response()->json([self::BODY => null, self::MESSAGE => $tokenData['result']])->setStatusCode(403);
        }
        $uuid = null ;
        if (isset($tokenData['auid'])) {
            $uuid = $tokenData['auid'];
        }
        if (isset($tokenData['body']['auid'])) {
            $uuid = $tokenData['body']['auid'];
        }

        // check if code is unavailable
        if ($discountCode['cancel_date']) {
            $cancelDate = Verta::instance($discountCode['cancel_date']);
            return response()->json([self::BODY => null, self::MESSAGE => trans('messages.cancel_date', ['date' => $cancelDate])])->setStatusCode(403);
        }
        // check if usage limit has finished
        if ($discountCode['usage_count'] >= $discountCode['usage_limit']) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.usage_limit')])->setStatusCode(403);
        }
        // check if usage limit per user has finished
        if ($discountCode->usageLogs()->where('uuid', $uuid)->count() >= $discountCode['usage_limit_per_user']) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.usage_limit_per_user')])->setStatusCode(403);
        }
        // check when access type is private if user has access to use the code
        if (($discountCode['access_type'] === 'private') && !$discountCode->users()->where('uuid', $uuid)->exists()) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.user_limit')])->setStatusCode(403);
        }

        // check when has market is true if user has access to current market version
        $market = SmallHelper::prepareMarket($validator->validated()['market']['name'], $request->input('market.version'));
        if (((boolean)$discountCode['has_market'] === true) && !$discountCode->markets()->where($market)->exists()) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.market_limit')])->setStatusCode(403);
        }


        $features = (new DiscountCodeGroups())->find($discountCode['group_id'])->features;
        $preparedFeatures = (new DiscountCodeFeatures())->prepareFeaturesToResponse($features);
        $result = [
            'uuid' => $uuid,
            'code' => $discountCode['code'],
            'first_by' => (boolean)$discountCode['first_buy'],
            'features' => $preparedFeatures,
        ];
        $result['token'] = JwtHelper::encodeJwt('HS512',config('settings.client_jwt.key'), $result, config('settings.client_jwt.expiration'));
        return response()->json([self::BODY => $result, self::MESSAGE => null])->setStatusCode(200);

    }



}
