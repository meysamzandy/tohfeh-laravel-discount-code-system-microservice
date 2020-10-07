<?php

namespace App\Http\Controllers;

use App\Http\Helper\JwtHelper;
use App\Http\Helper\SmallHelper;
use App\Http\Helper\ValidatorHelper;
use App\Models\DiscountCode;
use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class CodeProcessing extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';

    public function code(Request $request, DiscountCode $discountCode)
    {
//        $data = [
//            'uuid' => '2d3c9de4-3831-4988-8afb-710fda3e740c',
//        ];
//        $jwt = JwtHelper::encodeJwt(config('settings.user_management_jwt.key'), $data, 36000);
//        dd($jwt);



        // validate market data
        $validator = (new ValidatorHelper)->codeProcessingValidator($request->post());
        if ($validator->fails()) {
            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(400);
        }

        $tokenData = JwtHelper::decodeJwt(config('settings.user_management_jwt.key'), $request->input('user_token'));
        // check if token is invalid
        if (!$tokenData['result_status']) {
            return response()->json([self::BODY => null, self::MESSAGE => $tokenData['result']])->setStatusCode(403);
        }
        
        $uuid = $tokenData['result']['body']['uuid'] ?? null;

        $market = SmallHelper::prepareMarket($validator->validated()['market']['name'], $request->input('market.version'));

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
        $result['token'] = JwtHelper::encodeJwt(config('settings.client_jwt.key'), $result, 36000);
        return response()->json([self::BODY => $result, self::MESSAGE => null])->setStatusCode(200);

    }



}
