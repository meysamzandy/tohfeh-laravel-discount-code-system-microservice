<?php

namespace App\Http\Controllers;

use App\Models\UsageLog;


class UsageLogController extends Controller
{


    /**
     * @param $code_id
     * @param $code
     * @param $uuid
     * @return object|null
     */
    public function insertUsageLog($code_id, $code, $uuid)
    {
        try {
            return UsageLog::create([
                'code_id' => $code_id,
                'code' => $code,
                'uuid' => $uuid
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * @param string $code
     * @param null $uuid
     * @return int
     */
    public function countUsageLog(string $code, $uuid = null): int
    {
        try {
            $usage = UsageLog::query()->where([
                'uuid' => $uuid,
                'code' => $code,
            ])->count();
        } catch (\Exception $e) {
            $usage = 0;
        }
        return $usage;
    }
}
