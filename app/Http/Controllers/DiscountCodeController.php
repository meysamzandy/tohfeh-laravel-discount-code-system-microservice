<?php

namespace App\Http\Controllers;

use App\Http\Helper\SmallHelper;
use App\Http\Helper\ValidatorHelper;
use App\Jobs\ProcessAutoCodeCreation;
use App\Models\DiscountCode;
use App\Models\DiscountCodeFeatures;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;


class DiscountCodeController extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';
    protected $body = null;
    protected $message;
    protected $statusCode = 400;

    protected $input ;
    public function __construct(Request $request)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /**

         * @get('/api/admin/code')
         * @name('generated::SpQdL4Myny9fDaQt')
         * @middlewares(api, CheckToken)
         */
        //
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        /**

         * @post('/api/admin/code')
         * @name('generated::U0vD3nBYAC2Z7UAZ')
         * @middlewares(api, CheckToken)
         */


        $validator = (new ValidatorHelper)->creationCodeValidator($request->post());

        if ($validator->fails()) {

            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(403);

        }

        $isFeatureOk = (new DiscountCodeFeatures)->checkFeatureBeforeInsert($validator->validated()['features']) ;

        if (!$isFeatureOk) {

            return response()->json([self::BODY => null, self::MESSAGE => __('messages.checkDateIntervalAndPlan')])->setStatusCode(403);

        }
        $result = (new DiscountCode)->createCode($validator->validated());

//        ProcessAutoCodeCreation::dispatch(new DiscountCode,$validator->validated());
        return response()->json([self::BODY => $result[self::BODY], self::MESSAGE => $result[self::MESSAGE]])->setStatusCode($result[self::STATUS_CODE]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DiscountCode $discountCode
     * @return Response
     */
    public function update(Request $request, DiscountCode $discountCode)
    {
        /**

         * @patch('/api/admin/code/{id}')
         * @name('generated::VqzI6Ri8PiwGP0Qs')
         * @middlewares(api, CheckToken)
         */
        //
    }


}
