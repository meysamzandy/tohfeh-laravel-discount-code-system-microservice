<?php

namespace App\Http\Controllers;

use App\Http\Helper\SmallHelper;
use App\Models\DiscountCode;
use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Illuminate\Http\Request;

class DiscountCodeGroupController extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';
    protected $body;
    protected $message;
    protected $statusCode = 400;

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
        $requestParams = (new DiscountCodeGroups())->getParams();
        $query = DiscountCodeGroups::query();

        $data = SmallHelper::fetchList($requestParams, $query, $request, $page, $limit, $orderColumn, $orderBy);

        return response()->json([self::BODY => $data[self::BODY], self::MESSAGE => $data[self::MESSAGE]])->setStatusCode($data[self::STATUS_CODE]);

    }
}
