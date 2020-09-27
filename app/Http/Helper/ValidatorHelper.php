<?php


namespace App\Http\Helper;


use Illuminate\Support\Facades\Validator;

class ValidatorHelper
{

    public function creationCodeValidator($data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data,
            [
                'group_name' => 'required|string|min:1|max:254',
                'series' => 'nullable|string|min:1|max:100',
                'created_type' => 'required|in:auto,manual',
                'creation_code_count' => 'exclude_if:created_type,manual|required|min:1|max:100000',
                'code' => 'exclude_if:created_type,auto|required|min:6|max:100',
                'access_type' => 'required|in:public,private',
                'uuid_list' => 'exclude_if:access_type,public|required|array|min:1|max:500',
                'uuid_list.*' => 'uuid',
                'usage_limit' => 'required|numeric|min:1',
                'usage_limit_per_user' => 'required|numeric|min:1',
                'first_buy' => 'required|boolean',
                'has_market' => 'required|boolean',
                'market_name' => 'exclude_if:has_market,false|required|string|min:1|max:40',
                'version_major' => 'exclude_if:has_market,false|required|numeric|min:0|max:999',
                'version_minor' => 'exclude_if:has_market,false|required|numeric|min:0|max:999',
                'version_patch' => 'exclude_if:has_market,false|required|numeric|min:0|max:999',
                'plan_id' => 'required|numeric',
                'start_time' => 'required|date|after_or_equal:now',
                'end_time' => 'required|date|after:start_time',
                'code_type' => 'required|in:percent,price,free',
                'percent' => 'exclude_unless:code_type,percent|required|numeric|min:1|max:100',
                'limit_percent_price' => 'nullable|numeric|min:1|max:100',
                'price' => 'exclude_unless:code_type,price|required|numeric|min:1',
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
                'after_or_equal' => __('messages.after_or_equal'),
            ]);
    }
}