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
use Illuminate\Validation\ValidationException;

class ProcessCodeForAnonymousUser extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';


    /**
     * @param Request $request
     * @param DiscountCode $discountCode
     * @return JsonResponse|object
     * @throws ValidationException
     */
    public function code(Request $request, DiscountCode $discountCode)
    {

        // validate market data
        $validator = (new ValidatorHelper)->marketValidator($request->post());
        if ($validator->fails()) {
            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(400);
        }


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

        // check when has market is true if user has access to current market version

        if (((boolean)$discountCode['has_market'] === true) && !$discountCode->markets()->where($market)->exists()) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.market_limit')])->setStatusCode(403);
        }

        $features = (new DiscountCodeGroups())->find($discountCode['group_id'])->features;
        $preparedFeatures = (new DiscountCodeFeatures())->prepareFeaturesToResponse($features);
        $result = [
            'code' => $discountCode['code'],
            'first_by' => (boolean)$discountCode['first_buy'],
            'features' => $preparedFeatures,
        ];
        return response()->json([self::BODY => $result, self::MESSAGE => null])->setStatusCode(200);
    }
}
