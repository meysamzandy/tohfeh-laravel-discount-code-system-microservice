<?php

namespace App\Http\Controllers;

use App\Http\Helper\ValidatorHelper;
use App\Jobs\ProcessAutoCodeCreation;
use App\Models\DiscountCode;
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
    protected $body;
    protected $message;
    protected $statusCode = 400;

    protected $input;

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
    public function store(Request $request): JsonResponse
    {
        /**
         * @post('/api/admin/code')
         * @name('generated::U0vD3nBYAC2Z7UAZ')
         * @middlewares(api, CheckToken)
         */

        // validate code data
        $validator = (new ValidatorHelper)->creationCodeValidator($request->post());

        if ($validator->fails()) {

            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(400);

        }
        // validate Feature Array
        $isFeatureOk = (new ValidatorHelper)->validateFeatureArray($validator->validated()['features']);

        if (!$isFeatureOk) {

            return response()->json([self::BODY => null, self::MESSAGE => __('messages.checkDateIntervalAndPlan')])->setStatusCode(400);

        }
        $data = $validator->validated();

        // if code created_type is auto dispatch a job in queue
        if ($data['created_type'] === 'auto') {

            ProcessAutoCodeCreation::dispatch($data)->delay(1);
            return response()->json([self::BODY => null, self::MESSAGE => trans('messages.codeQueued', ['count' => $data['creation_code_count']])])->setStatusCode(200);
        }

        $result = (new DiscountCode)->createCode($data);

        return response()->json([self::BODY => $result[self::BODY], self::MESSAGE => $result[self::MESSAGE]])->setStatusCode($result[self::STATUS_CODE]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        // validate code data
        $validator = (new ValidatorHelper)->updateCodeValidator($request->post());

        if ($validator->fails()) {

            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(400);

        }
        try {
            $discountCode = DiscountCode::find((int)$id);

            $discountCode->update([
                'usage_limit' => $validator->validated()['usage_limit']
            ]);

            return response()->json([self::BODY => null, self::MESSAGE => null])->setStatusCode(202);
        } catch (\Exception $e) {

            return response()->json([self::BODY => null, self::MESSAGE => $e->getMessage()])->setStatusCode(417);

        }
    }

}
