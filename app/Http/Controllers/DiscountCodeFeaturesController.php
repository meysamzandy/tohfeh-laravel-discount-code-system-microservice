<?php

namespace App\Http\Controllers;

use App\Http\Helper\SmallHelper;
use App\Http\Helper\ValidatorHelper;
use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class DiscountCodeFeaturesController extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';
    protected $body;
    protected $message;
    protected $statusCode = 400;


    /**
     * @param Request $request
     * @return JsonResponse|object
     */
    public function index(Request $request)
    {
        /**
         * @get('/api/admin/feature')
         * @name('generated::ChJokdj7FvvU1tZg')
         * @middlewares(api, CheckToken)
         */
        //

        // get page and limit
        [$page, $limit] = SmallHelper::paginationParams($request);
        // get query params
        [$orderColumn, $orderBy] = SmallHelper::orderParams($request);
        $requestParams = (new DiscountCodeFeatures())->getParams();
        $query = DiscountCodeFeatures::query();

        $data = SmallHelper::fetchList($requestParams, $query, $request, $page, $limit, $orderColumn, $orderBy);

        return response()->json([self::BODY => $data[self::BODY], self::MESSAGE => $data[self::MESSAGE]])->setStatusCode($data[self::STATUS_CODE]);

    }


    /**
     * @param Request $request
     * @return JsonResponse|object
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        /**
         * @post('/api/admin/feature')
         * @name('generated::DL28iPHUGZZFVLxe')
         * @middlewares(api, CheckToken)
         */
        //

        // validate code data
        $validator = (new ValidatorHelper)->creationFeatureValidator($request->post());

        if ($validator->fails()) {

            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(400);

        }
        // check if exist common feature in db
        $checkFeature = (new DiscountCodeFeatures)->checkFeatureBeforeAddToExistingCode($validator->validated()['group_id'], $validator->validated()['features']);
        if (!$checkFeature) {
            return response()->json([self::BODY => null, self::MESSAGE => __('messages.checkDateIntervalAndPlan')])->setStatusCode(400);
        }
        // add features
        $result = (new DiscountCodeFeatures)->addFeaturesToExistingCode($validator->validated()['group_id'], $validator->validated()['features']);

        return response()->json([self::BODY => $result[self::BODY], self::MESSAGE => $result[self::MESSAGE]])->setStatusCode($result[self::STATUS_CODE]);
    }


    /**
     * @param $id
     * @return JsonResponse|object
     */
    public function destroy(DiscountCodeFeatures $id)
    {
        /**
         * @delete('/api/admin/feature/{id}')
         * @name('generated::DafiL150qa0CqG7W')
         * @middlewares(api, CheckToken)
         */
        //
        try {

            // delete feature
            $id->delete();

            return response()->json([self::BODY => null, self::MESSAGE => __('messages.deletion_failed')])->setStatusCode(204);

        } catch (Exception $e) {

            return response()->json([self::BODY => null, self::MESSAGE => $e->getMessage()])->setStatusCode(417);

        }
    }
}
