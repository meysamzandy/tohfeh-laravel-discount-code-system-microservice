<?php


namespace App\Http\Helper;


use Illuminate\Support\Facades\Validator;

class ValidatorHelper
{

    public function creationCodeValidator($data)
    {
        return Validator::make($data,
            [
                'group_name' => 'required|string|min:1|max:254',
                'series' => 'nullable|string|min:1|max:100',
                'created_type' => 'required|in:auto,manual',
                'code' => 'bail|required_if:created_type,manual|min:6|max:100|required_unless:created_type,auto',
                'access_type' => 'required|in:public,private',
                'uuid_list' => 'required_if:access_type,private|array|min:1|max:500',
                'uuid_list.*' => 'uuid',
                'usage_limit' => 'required|numeric|min:1',
                'usage_limit_per_user' => 'required|numeric|min:1',
                'first_buy' => 'required|boolean',
                'has_market' => 'required|boolean',
                'market_name' => 'bail|required_if:has_market,true|string|min:1|max:40',
                'version_major' => 'required_with:market_name|numeric|min:0|max:999',
                'version_minor' => 'required_with:market_name|numeric|min:0|max:999',
                'version_patch' => 'required_with:market_name|numeric|min:0|max:999',
                'plan_id' => 'required|numeric',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'code_type' => 'required|in:percent,price,free',
                'percent' => 'required_if:code_type,percent|numeric|min:1|max:100',
                'limit_percent_price' => 'nullable|numeric|min:1|max:100',
                'price' => 'required_if:code_type,price|numeric|min:1',
                'description' => 'nullable|string|min:1|max:254',

            ]
            , [
                'required' => __('messages.required'),
                'string' => __('messages.string'),
                'max' => __('messages.max'),
                'min' => __('messages.min'),
                'unique' => __('messages.unique'),
                'required_if' => __('messages.required_if'),
                'required_unless' => __('messages.required_unless'),
                'in' => __('messages.in'),
                'array' => __('messages.array'),
                'numeric' => __('messages.numeric'),
                'boolean' => __('messages.boolean'),
                'required_with' => __('messages.required_with'),
                'date' => __('messages.date'),
                'after' => __('messages.after'),
                'uuid' => __('messages.uuid'),
            ]);
    }
}