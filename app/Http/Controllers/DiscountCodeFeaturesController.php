<?php

namespace App\Http\Controllers;

use App\Models\DiscountCodeFeatures;
use Illuminate\Http\Request;

class DiscountCodeFeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DiscountCodeFeatures  $discountCodeFeatures
     * @return \Illuminate\Http\Response
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DiscountCodeFeatures  $discountCodeFeatures
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscountCodeFeatures $discountCodeFeatures)
    {
        /**

         * @delete('/api/admin/feature/{id}')
         * @name('generated::DafiL150qa0CqG7W')
         * @middlewares(api, CheckToken)
         */
        //
    }
}
