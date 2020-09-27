<?php

namespace Tests\Unit;

use App\Http\Helper\ValidatorHelper;
use App\Models\DiscountCodeGroups;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ValidatorHelperTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
//        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeGroupsSeeder');
    }

    public function testCreationCodeValidator(): void
    {
        //check when all validation failed
        $data = [
            //code group
            'group_name' => '',
            'series' => '',
            //code property
            'created_type' => 'manuasl', // if auto code should be empty
            'code' => '',
            'access_type' => 'privatse',
            'uuid_list' => '',
            'usage_limit' => 0,
            'usage_limit_per_user' => 0,
            'first_buy' => 'false',
            'has_market' => true,
            'market_name' => '',
            'version_major' => '',
            'version_minor' => '',
            'version_patch' => '',
//            // code feature property
            'plan_id' => 'fds',
            'start_time' => date('Y-m-d H:i:s', strtotime('2020-09-22 10:25:38')),
            'end_time' => date('Y-m-d H:i:s', strtotime('2020-09-25 10:25:38')),
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => '',
            'description' => 'a sample text for descriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescrip
            tiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondesc
            descriptiondescriptiondescriptiondescriptionriptiondescriptiondescriptiondescriptiondescription',

        ];
        $result = (new ValidatorHelper)->creationCodeValidator($data);
        self::assertFalse($result->passes());

        //check when end_time is lower than start_time
        $data = [
            //code group
            'group_name' => 'test',
            'series' => '',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'code' => 'DDDDDDD',
            'access_type' => 'private',
            'uuid_list' => [
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
            ],
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => false,
            'market_name' => '',
            'version_major' => '',
            'version_minor' => '',
            'version_patch' => '',
//            // code feature property
            'plan_id' => 1212,
            'start_time' => date('Y-m-d H:i:s', strtotime('2020-09-22 10:25:38')),
            'end_time' => date('Y-m-d H:i:s', strtotime('2020-09-20 10:25:38')),
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',

        ];
        $result = (new ValidatorHelper)->creationCodeValidator($data);
        self::assertFalse($result->passes());

        //check when has_market is false & access_type is private & created_type is manual
        $data = [
            //code group
            'group_name' => 'test',
            'series' => '',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'code' => 'DDDDDDD',
            'access_type' => 'private',
            'uuid_list' => [
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
            ],
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => false,
            'market_name' => '',
            'version_major' => '',
            'version_minor' => '',
            'version_patch' => '',
//            // code feature property
            'plan_id' => 1212,
            'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
            'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',

        ];
        $result = (new ValidatorHelper)->creationCodeValidator($data);
        self::assertTrue($result->passes());

        //check when created_type is auto & access_type is public &  has_market is true
        $data = [
            //code group
            'group_name' => 'test',
            'series' => '',
            //code property
            'created_type' => 'auto', // if auto code should be empty
            'creation_code_count' => 10 ,
            'code' => '',
            'access_type' => 'public',
            'uuid_list' => '',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => true,
            'market_name' => 'myket',
            'version_major' => 1,
            'version_minor' => 10,
            'version_patch' => 0,
//            // code feature property
            'plan_id' => 1212,
            'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
            'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',
            
        ];
        $result = (new ValidatorHelper)->creationCodeValidator($data);
        self::assertTrue($result->passes());

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

}
