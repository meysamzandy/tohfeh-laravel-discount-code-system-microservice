<?php


namespace App\Http\Helper;


use Illuminate\Support\Facades\Validator;

class ValidatorHelper
{

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function creationCodeValidator($data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data,
            [
                'group_name' => 'required|string|min:1|max:254',
                'series' => 'nullable|string|min:1|max:100',
                'created_type' => 'required|in:auto,manual',
                'creation_code_count' => 'exclude_if:created_type,manual|required|numeric|min:1|max:10000',
                'prefix' => 'exclude_if:created_type,manual|required|min:1|max:10',
                'stringType' => 'exclude_if:created_type,manual|required|in:0,1,2',
                'code' => 'exclude_if:created_type,auto|required|min:6|max:12',
                'access_type' => 'required|in:public,private',
                'uuid_list' => 'exclude_if:created_type,auto|exclude_if:access_type,public|required|array|min:1|max:10000',
                'uuid_list.*' => 'uuid',
                'usage_limit' => 'required|numeric|min:1',
                'usage_limit_per_user' => 'required|numeric|min:1',
                'first_buy' => 'required|boolean',
                'has_market' => 'required|boolean',
                'market' => 'exclude_if:has_market,false|required|array|min:1|max:20',
                'market.*.market_name' => 'exclude_if:has_market,false|required|string|min:1|max:40',
                'market.*.version_major' => 'exclude_if:has_market,false|required|numeric|min:0|max:999',
                'market.*.version_minor' => 'exclude_if:has_market,false|required|numeric|min:0|max:999',
                'market.*.version_patch' => 'exclude_if:has_market,false|required|numeric|min:0|max:999',
                'features' => 'required|array|min:1|max:50',
                'features.*.plan_id' => 'required|numeric',
                'features.*.start_time' => 'required|date|after_or_equal:now',
                'features.*.end_time' => 'required|date|after:features.*.start_time',
                'features.*.code_type' => 'required|in:percent,price,free',
                'features.*.percent' => 'exclude_unless:features.*.code_type,percent|required|numeric|min:1|max:100',
                'features.*.limit_percent_price' => 'exclude_unless:features.*.code_type,percent|nullable|numeric|min:1|max:100',
                'features.*.price' => 'exclude_unless:features.*.code_type,price|required|numeric|min:1',
                'features.*.description' => 'nullable|string|min:1|max:254',

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

    public function creationFeatureValidator($data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data,
            [
                'group_id' => 'required|numeric|min:1|exists:discount_code_groups,id',
                'features' => 'required|array|min:1|max:50',
                'features.*.plan_id' => 'required|numeric',
                'features.*.start_time' => 'required|date|after_or_equal:now',
                'features.*.end_time' => 'required|date|after:features.*.start_time',
                'features.*.code_type' => 'required|in:percent,price,free',
                'features.*.percent' => 'exclude_unless:features.*.code_type,percent|required|numeric|min:1|max:100',
                'features.*.limit_percent_price' => 'exclude_unless:features.*.code_type,percent|nullable|numeric|min:1|max:100',
                'features.*.price' => 'exclude_unless:features.*.code_type,price|required|numeric|min:1',
                'features.*.description' => 'nullable|string|min:1|max:254',

            ]
            , [
                'required' => __('messages.required'),
                'string' => __('messages.string'),
                'max' => __('messages.max'),
                'min' => __('messages.min'),
                'array' => __('messages.array'),
                'in' => __('messages.in'),
                'numeric' => __('messages.numeric'),
                'date' => __('messages.date'),
                'after' => __('messages.after'),
                'after_or_equal' => __('messages.after_or_equal'),
                'exists' => __('messages.exists')
            ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function callBackDataValidator($data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data,
            [
                'uuid' => 'required|uuid',
                'code' => 'required|min:6|max:12|exists:discount_codes,code',
                'usage_result' => 'required|boolean',
            ]
            , [
                'required' => __('messages.required'),
                'boolean' => __('messages.boolean'),
                'uuid' => __('messages.uuid'),
                'max' => __('messages.max'),
                'min' => __('messages.min'),
                'exists' => __('messages.exists')

            ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function marketValidator($data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'market' => 'required',
            'market.name' => 'required|string|min:1|max:40',
            'market.version' => 'required|string',
        ],[
            'required' => __('messages.required'),
            'string' => __('messages.string'),
            'max' => __('messages.max'),
            'min' => __('messages.min'),
        ]);
    }

    public function updateCodeValidator($data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'usage_limit' => 'required|numeric|min:1',
        ],[
            'required' => __('messages.required'),
            'numeric' => __('messages.numeric'),
            'min' => __('messages.min'),
        ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function userTokenValidator($data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'user_token' => 'required',
        ],[
            'required' => __('messages.required'),
        ]);
    }

    /**
     * @param array $features
     * @return bool
     */
    public function validateFeatureArray(array $features): bool
    {
        $count = count($features) - 1;
        $checkinArray = $features;
        for ($i = 0; $i < $count; $i++) {
            foreach ($checkinArray as $key => $value) {
                if ($i === $key) {
                    continue;
                }

                $IntervalStart_timeStatus = (new SmallHelper)->checkDateInterval($features[$i]['start_time'], $value['start_time'], $value['end_time']);
                $IntervalEnd_timeStatus = (new SmallHelper)->checkDateInterval($features[$i]['end_time'], $value['start_time'], $value['end_time']);
                if (($features[$i]['plan_id'] === $value['plan_id']) && $IntervalStart_timeStatus) {
                    return false;
                }
                if (($features[$i]['plan_id'] === $value['plan_id']) && $IntervalEnd_timeStatus) {
                    return false;
                }

            }
        }
        return true;
    }


}