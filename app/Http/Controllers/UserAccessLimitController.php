<?php

namespace App\Http\Controllers;

use App\Models\UserAccessLimit;
use Exception;

class UserAccessLimitController extends Controller
{
    /**
     * @param int $code_id
     * @param $uuid
     * @return object|null
     */
    public function insertUserAccessLimit(int $code_id, $uuid)
    {
        try {
            return UserAccessLimit::create([
                'code_id' => $code_id,
                'uuid' => $uuid
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param int $code_id
     * @param $uuid
     * @return bool
     */
    public function selectUserAccessLimit(int $code_id, $uuid): bool
    {
        try {
            return UserAccessLimit::query()->where([
                'code_id' => $code_id,
                'uuid' => $uuid,
            ])->exists();
        } catch (Exception $e) {
            return false ;
        }
    }
}
