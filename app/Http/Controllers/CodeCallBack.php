<?php

namespace App\Http\Controllers;

use App\Http\Helper\JwtHelper;
use App\Http\Helper\ValidatorHelper;
use App\Models\DiscountCode;
use App\Models\UsageLog;
use Illuminate\Http\Request;

class CodeCallBack extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';

    public function callback(Request $request)
    {
//        $data = [
//            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
//            'code' => 'meysamndys',
//            'usage_result' => true,
//            'source' => api,
//            'offset' => 12,
//        ];
//        $jwt = JwtHelper::encodeJwt('HS512',config('settings.client_jwt.key'), $data, 36000) ;
//        dd($jwt);

        // check if data:token doesn't exist
        if (!$request->filled('data')) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.dataTokenNotExist')])->setStatusCode(400);
        }

        // decode token in data
        $tokenData = JwtHelper::decodeJwt('HS512',config('settings.client_jwt.key'), $request->input('data'));

        // check if token is valid
        if (!$tokenData['result_status']) {
            return response()->json([self::BODY => null, self::MESSAGE => $tokenData['result']])->setStatusCode(403);
        }

        // if data in token is not valid
        $validator = (new ValidatorHelper)->callBackDataValidator($tokenData['result']['body']);
        if ($validator->fails()) {
            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(400);
        }
        // get valid data from token
         $data = $validator->validated();

        // check if usage_result from sale system is false there is no result to show
        if (!$data['usage_result']) {
            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(204);
        }

        // get the code from db
        $codeData = DiscountCode::query()->where('code',$data['code'])->first() ;

        // check if record exist
        if (!$codeData) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.codeNotExist')])->setStatusCode(400);
        }

        // store usage log
        $usageLog = new UsageLog([
            'code_id'=>$codeData['id'],
            'code'=> $codeData['code'] ,
            'uuid' =>$data['uuid'],
            'source' =>$data['source'],
            'offset' => isset($data['offset']) ? $data['offset'] :null
            ]);
        $usageSaveResult = $usageLog->save();

        // check if store usage log successfully
        if (!$usageSaveResult) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.exceptionError')])->setStatusCode(417);
        }

        // update code usage_count
        $codeData['usage_count'] += 1;
        $codeSaveResult = $codeData->save();

        // check if update update code usage_count successfully
        if (!$codeSaveResult) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.exceptionError')])->setStatusCode(417);
        }

        return response()->json([self::BODY => null, self::MESSAGE => null])->setStatusCode(201);

    }
}
