<?php

namespace App\Http\Controllers;

use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /**
         * @get('/api/admin/feature')
         * @name('generated::ChJokdj7FvvU1tZg')
         * @middlewares(api, CheckToken)
         */
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        /**
         * @post('/api/admin/feature')
         * @name('generated::DL28iPHUGZZFVLxe')
         * @middlewares(api, CheckToken)
         */
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DiscountCodeFeatures $discountCodeFeatures
     * @return Response
     */
    public function update(Request $request, DiscountCodeFeatures $discountCodeFeatures)
    {
        /**
         * @patch('/api/admin/feature/{id}')
         * @name('generated::DYWGCPDTRY6sMIyG')
         * @middlewares(api, CheckToken)
         */
        //
    }


    public function destroy($id)
    {
        /**
         * @delete('/api/admin/feature/{id}')
         * @name('generated::DafiL150qa0CqG7W')
         * @middlewares(api, CheckToken)
         */
        //
        try {

            $feature = DiscountCodeFeatures::query()->find((int)$id);
            if (!$feature) {
                return response()->json([self::BODY => null, self::MESSAGE => __('messages.feature_not_exit')])->setStatusCode(404);
            }

            // delete feature
            $deleteResult = $feature->delete();
            if (!$deleteResult) {
                return response()->json([self::BODY => null, self::MESSAGE => __('messages.deletion_failed')])->setStatusCode(417);
            }

            // delete group and code and all stuff related to the group and code if there is no any feature in the group
            $count = DiscountCodeFeatures::query()->where('group_id', $feature->group_id)->count();
            if ($count === 0) {
                DiscountCodeGroups::destroy($feature->group_id);
                return response()->json([self::BODY => null, self::MESSAGE => __('messages.deletion_successful')])->setStatusCode(204);
            }

            return response()->json([self::BODY => null, self::MESSAGE => __('messages.delete_full_successful')])->setStatusCode(204);

        } catch (Exception $e) {

            return response()->json([self::BODY => null, self::MESSAGE => $e->getMessage()])->setStatusCode(417);

        }
    }
}
