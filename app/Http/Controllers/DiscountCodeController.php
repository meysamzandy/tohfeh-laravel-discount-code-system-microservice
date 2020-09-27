<?php

namespace App\Http\Controllers;

use App\Http\Helper\SmallHelper;
use App\Http\Helper\ValidatorHelper;
use App\Models\DiscountCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;


class DiscountCodeController extends Controller
{
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

            return response()->json($validator->errors(),403);

        }

        (new DiscountCode)->createCode($validator->validated());

        return response()->json($validator->validated(),200);
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
