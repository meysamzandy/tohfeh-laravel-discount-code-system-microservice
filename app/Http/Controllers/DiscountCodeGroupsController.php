<?php

namespace App\Http\Controllers;

use App\Models\DiscountCodeGroups;
use Exception;

class DiscountCodeGroupsController extends Controller
{

    /**
     * @param string $group_name
     * @param null $series
     * @return object|null
     */
    public function insertGroup(string $group_name, $series = null)
    {
        try {
            return DiscountCodeGroups::create([
                'group_name' => $group_name,
                'series' => $series,
            ]);
        } catch (Exception $e) {
            return null;
        }
    }
}
