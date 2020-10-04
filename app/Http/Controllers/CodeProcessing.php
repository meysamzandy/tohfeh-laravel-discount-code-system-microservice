<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class CodeProcessing extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';
    
    public function code(DiscountCode $discountCode)
    {
        $uuid = '2d3c9de4-3831-4988-8afb-710fda2e740c';
        $market = [
            'market_name' => 'caffebazar',
            "version_major" => 1,
            "version_minor" => 0,
            "version_patch" => 5
        ];
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
        if ($discountCode->usageLogs()->where('uuid',$uuid)->count() >= $discountCode['usage_limit_per_user']) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.usage_limit_per_user')])->setStatusCode(403);
        }
        // check when access type is private if user has access to use the code
        if (($discountCode['access_type'] === 'private') && !$discountCode->users()->where('uuid', $uuid)->exists()) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.user_limit')])->setStatusCode(403);
        }
        // check when has market is true if user has access to current market version
        if ( ((boolean) $discountCode['has_market'] === true) && !$discountCode->markets()->where($market)->exists()) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.market_limit')])->setStatusCode(403);
        }
        dd($discountCode);


    }
}
