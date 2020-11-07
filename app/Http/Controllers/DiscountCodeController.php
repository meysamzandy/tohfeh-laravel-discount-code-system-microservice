<?php

namespace App\Http\Controllers;

use App\Http\Helper\SmallHelper;
use App\Http\Helper\ValidatorHelper;
use App\Jobs\ProcessAutoCodeCreation;
use App\Models\DiscountCode;
use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

    /**
     * @param Request $request
     * @return JsonResponse|object
     */
    public function index(Request $request)
    {
        /**
         * @get('/api/admin/code')
         * @name('generated::SpQdL4Myny9fDaQt')
         * @middlewares(api, CheckToken)
         */
        //
        // get page and limit
        [$page, $limit] = SmallHelper::paginationParams($request);
        // get query params
        [$orderColumn, $orderBy] = SmallHelper::orderParams($request);
        $requestParams = (new DiscountCode())->getParams();
        $query = DiscountCode::query();

        $data = SmallHelper::fetchList($requestParams, $query, $request, $page, $limit, $orderColumn, $orderBy);

        return response()->json([self::BODY => $data[self::BODY], self::MESSAGE => $data[self::MESSAGE]])->setStatusCode($data[self::STATUS_CODE]);

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
            if (!$discountCode) {
                return response()->json([self::BODY => null, self::MESSAGE => __('messages.codeNotExist')])->setStatusCode(404);
            }
            $discountCode->update([
                'usage_limit' => $validator->validated()['usage_limit']
            ]);

            return response()->json([self::BODY => null, self::MESSAGE => null])->setStatusCode(202);
        } catch (\Exception $e) {

            return response()->json([self::BODY => null, self::MESSAGE => $e->getMessage()])->setStatusCode(417);

        }
    }


    /**
     * @param Request $request
     * @return JsonResponse|object
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        // validate code data
        $seriesValidator = (new ValidatorHelper)->massiveCodeValidator($request->post());

        if ($seriesValidator->fails()) {

            return response()->json([self::BODY => null, self::MESSAGE => $seriesValidator->errors()])->setStatusCode(400);

        }
        $data = $seriesValidator->validated();

        // validate Feature Array
        $isFeatureOk = (new ValidatorHelper)->validateFeatureArray($data['features']);

        if (!$isFeatureOk) {

            return response()->json([self::BODY => null, self::MESSAGE => __('messages.checkDateIntervalAndPlan')])->setStatusCode(400);

        }

        //check if has not series at the first
        $group = DiscountCodeGroups::query()->where('series', $data['series'])->first();

        $result = (new DiscountCode)->createMassiveCode($data, $group);

        return response()->json([self::BODY => $result[self::BODY], self::MESSAGE => $result[self::MESSAGE]])->setStatusCode($result[self::STATUS_CODE]);
    }


    public function destroy(DiscountCode $id)
    {
        try {
            // delete  code
            $id->delete();

            return response()->json([self::BODY => null, self::MESSAGE => __('messages.deletion_successful')])->setStatusCode(204);

        } catch (Exception $e) {

            return response()->json([self::BODY => null, self::MESSAGE => $e->getMessage()])->setStatusCode(417);

        }

    }

}
