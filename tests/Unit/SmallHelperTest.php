<?php

namespace Tests\Unit;

use App\Http\Helper\SmallHelper;
use App\Models\DiscountCodeFeatures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class SmallHelperTest extends TestCase
{

    // check if string change code to uppercase correctly
    public function testChangeCodeToUppercase(): void
    {
        $result = SmallHelper::changeCodeToUppercase('meysam120m');
        self::assertNotNull($result);
        self::assertIsString($result);
        self::assertSame(mb_strtoupper('meysam120m', 'utf-8'), $result);
        self::assertSame(mb_strtolower($result, 'utf-8'), 'meysam120m');

    }

    // check code generator
    public function testCodeGenerator(): void
    {
        $prefix = strtoupper('test_');
        $stringType = config('settings.generatorString.bothCharacter');
        $length = config('settings.automateCodeLength');
        $code = SmallHelper::codeGenerator($prefix, $stringType, $length);
        self::assertIsString($code);
        self::assertNotNull($code);
        self::assertNotFalse(strpos($code, $prefix));
        self::assertEquals($length, strlen(str_replace($prefix, '', $code)));
        $mock = Mockery::mock(SmallHelper::class, 'codeGenerator');
        $mock->shouldReceive('codeGenerator')->once()->andReturn(
            null
        );
        self::assertInstanceOf(SmallHelper::class, $mock);
        $code = $mock::codeGenerator($prefix, $stringType, $length);
        self::assertNull($code);
        Mockery::close();
    }

    public function testCheckTime(): void
    {
        // if date is between two date
        $dateBegin = '2020-09-22 10:25:38';
        $dateEnd = '2020-09-25 13:25:38';

        $inputDate = '2020-09-23 13:25:38';
        $code = (new SmallHelper)->checkDateInterval($inputDate, $dateBegin, $dateEnd);
        assertTrue($code);

        $dateBegin = '2020-09-22 10:25:38';
        $dateEnd = '2020-09-25 13:25:38';

        $inputDate = '2020-09-25 13:25:39';
        $code = (new SmallHelper)->checkDateInterval($inputDate, $dateBegin, $dateEnd);
        assertFalse($code);
    }

    public function testPrepareDataForManualCodes(): void
    {
        $data = [
            "group_name" => "1کدهای تخفیف بهاره",
            "series" => "",
            "created_type" => "manual",
            "creation_code_count" => 20,
            "prefix" => "test|",
            "stringType" => 2,
            "code" => "meysamndys",
            "access_type" => "private",
            "uuid_list" => [
                "2d3c9de4-3831-4988-8afb-710fda2e740c",
                "2d3c9de4-3831-4988-8afb-710fda2e741c",
                "2d3c9de4-3831-4988-8afb-710fda2e742c"
            ],
            "usage_limit" => 100,
            "usage_limit_per_user" => 1,
            "first_buy" => false,
            "has_market" => true,
            "market" => [
                [
                    "market_name" => "myket",
                    "version_major" => 1,
                    "version_minor" => 2,
                    "version_patch" => 0
                ],
                [
                    "market_name" => "caffebazar",
                    "version_major" => 1,
                    "version_minor" => 0,
                    "version_patch" => 5
                ]
            ],
            "features" => [
                [
                    "plan_id" => 1210,
                    "start_time" => "2020-09-29 15=>56=>50",
                    "end_time" => "2020-09-30 20=>25=>38",
                    "code_type" => "percent",
                    "percent" => 10,
                    "limit_percent_price" => "",
                    "price" => 200,
                    "description" => "توضیح ندارد"
                ],
                [
                    "plan_id" => 1210,
                    "start_time" => "2020-10-20 15=>56=>51",
                    "end_time" => "2020-10-21 20=>25=>52",
                    "code_type" => "price",
                    "percent" => "",
                    "limit_percent_price" => "",
                    "price" => 1000,
                    "description" => "توضیح ندارد"
                ],
                [
                    "plan_id" => 1210,
                    "start_time" => "2020-10-27 15=>56=>58",
                    "end_time" => "2020-10-28 20=>25=>59",
                    "code_type" => "free",
                    "percent" => "",
                    "limit_percent_price" => "",
                    "price" => 1000,
                    "description" => "توضیح ندارد"
                ]
            ]
        ];
        [$groupData, $featuresData, $CodeData, $userListData, $marketData] = SmallHelper::prepareDataForManualCodes($data);
        self::assertIsArray($groupData);
        self::assertArrayHasKey('group_name', $groupData);
        self::assertIsArray($featuresData);
        self::assertArrayHasKey('plan_id', $featuresData[0]);
        self::assertIsArray($CodeData);
        self::assertArrayHasKey('code', $CodeData);
        self::assertSame(mb_strtoupper($data['code'], 'utf-8'), $CodeData['code']);
        self::assertIsArray($userListData);
        self::assertEquals('2d3c9de4-3831-4988-8afb-710fda2e740c', $userListData[0]);
        self::assertIsArray($marketData);
        self::assertArrayHasKey('market_name', $marketData[0]);
    }

    public function testPrepareDataForAutoCodes(): void
    {
        $data = [
            "group_name" => "1کدهای تخفیف بهاره",
            "series" => "",
            "created_type" => "auto",
            "creation_code_count" => 20,
            "prefix" => "test|",
            "stringType" => 2,
            "code" => "meysamndys",
            "access_type" => "private",
            "uuid_list" => [
                "2d3c9de4-3831-4988-8afb-710fda2e740c",
                "2d3c9de4-3831-4988-8afb-710fda2e741c",
                "2d3c9de4-3831-4988-8afb-710fda2e742c"
            ],
            "usage_limit" => 100,
            "usage_limit_per_user" => 1,
            "first_buy" => false,
            "has_market" => true,
            "market" => [
                [
                    "market_name" => "myket",
                    "version_major" => 1,
                    "version_minor" => 2,
                    "version_patch" => 0
                ],
                [
                    "market_name" => "caffebazar",
                    "version_major" => 1,
                    "version_minor" => 0,
                    "version_patch" => 5
                ]
            ],
            "features" => [
                [
                    "plan_id" => 1210,
                    "start_time" => "2020-09-29 15=>56=>50",
                    "end_time" => "2020-09-30 20=>25=>38",
                    "code_type" => "percent",
                    "percent" => 10,
                    "limit_percent_price" => "",
                    "price" => 200,
                    "description" => "توضیح ندارد"
                ],
                [
                    "plan_id" => 1210,
                    "start_time" => "2020-10-20 15=>56=>51",
                    "end_time" => "2020-10-21 20=>25=>52",
                    "code_type" => "price",
                    "percent" => "",
                    "limit_percent_price" => "",
                    "price" => 1000,
                    "description" => "توضیح ندارد"
                ],
                [
                    "plan_id" => 1210,
                    "start_time" => "2020-10-27 15=>56=>58",
                    "end_time" => "2020-10-28 20=>25=>59",
                    "code_type" => "free",
                    "percent" => "",
                    "limit_percent_price" => "",
                    "price" => 1000,
                    "description" => "توضیح ندارد"
                ]
            ]
        ];
        [$groupData, $featuresData, $CodeData, $marketData] = SmallHelper::prepareDataForAutoCodes($data);
        self::assertIsArray($groupData);
        self::assertArrayHasKey('group_name', $groupData);
        self::assertIsArray($featuresData);
        self::assertArrayHasKey('plan_id', $featuresData[0]);
        self::assertIsArray($CodeData);
        self::assertArrayNotHasKey('code', $CodeData);
        self::assertIsArray($marketData);
        self::assertArrayHasKey('market_name', $marketData[0]);
    }


    public function testReturnStatus(): void
    {
        $return = SmallHelper::returnStatus(true, 200, 'test', 'test message');
        self::assertIsArray($return);
        self::assertArrayHasKey('resultStats', $return);
        self::assertArrayHasKey('statusCode', $return);
        self::assertArrayHasKey('body', $return);
        self::assertArrayHasKey('message', $return);

    }

    public function testPrepareMarket(): void
    {
        $market = SmallHelper::prepareMarket('caffebazar', '1.2.3');
        self::assertIsArray($market);
        self::assertArrayHasKey('market_name', $market);
        self::assertArrayHasKey('version_major', $market);
        self::assertArrayHasKey('version_minor', $market);
        self::assertArrayHasKey('version_patch', $market);
        self::assertEquals('caffebazar', $market['market_name']);
        self::assertEquals(1, $market['version_major']);
        self::assertEquals(2, $market['version_minor']);
        self::assertEquals(3, $market['version_patch']);
    }


    public function testPaginationParams(): void
    {
        $request = new Request([], $_GET, [], [], [], []);
        $request->headers->set('Content-Type', 'application/json');
        $pagination = SmallHelper::paginationParams($request);

        self::assertIsArray($pagination);
        // page should be  1
        self::assertEquals(1, $pagination[0]);

        // limit should be  20
        self::assertEquals(20, $pagination[1]);

        /**************/
        $request = new Request(['page' => 5 , 'limit' => 300], $_GET, [], [], [], []);
        $request->headers->set('Content-Type', 'application/json');
        $pagination = SmallHelper::paginationParams($request);

        self::assertIsArray($pagination);
        // page should be Equals 5
        self::assertEquals(5, $pagination[0]);

        // limit should be Equals 300
        self::assertEquals(300, $pagination[1]);

        /**************/
        $request = new Request(['page' => 0 , 'limit' => 501], $_GET, [], [], [], []);
        $request->headers->set('Content-Type', 'application/json');
        $pagination = SmallHelper::paginationParams($request);

        self::assertIsArray($pagination);
        // page should be grader than 0
        self::assertGreaterThanOrEqual(1, $pagination[0]);

        // limit should be less than 501
        self::assertLessThanOrEqual(500, $pagination[1]);

    }

    public function testOrderParams(): void
    {

        $request = new Request([], $_GET, [], [], [], []);
        $request->headers->set('Content-Type', 'application/json');
        $pagination = SmallHelper::orderParams($request);

        self::assertIsArray($pagination);
        // page should be Equals created_at
        self::assertEquals('created_at', $pagination[0]);

        // limit should be Equals desc
        self::assertEquals('desc', $pagination[1]);

    }

    public function testFetchList(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeFeaturesSeeder');

        //// has no data
        $request = new Request(['page' => 30 , 'limit' => 300], $_GET, [], [], [], []);
        $request->headers->set('Content-Type', 'application/json');

        [$page, $limit] = SmallHelper::paginationParams($request);
        [$orderColumn, $orderBy] = SmallHelper::orderParams($request);

        $requestParams = (new DiscountCodeFeatures())->getParams();
        $query = DiscountCodeFeatures::query();
        $data = SmallHelper::fetchList($requestParams, $query, $request, $page, $limit, $orderColumn, $orderBy);

        self::assertFalse($data['resultStats']);
        self::assertEquals(200,$data['statusCode']);


        //// has data get all without filter
        $request = new Request(['page' => 0 , 'limit' => 300], $_GET, [], [], [], []);
        [$page, $limit] = SmallHelper::paginationParams($request);
        [$orderColumn, $orderBy] = SmallHelper::orderParams($request);

        $requestParams = (new DiscountCodeFeatures())->getParams();
        self::assertIsArray( $requestParams);
        self::assertEquals(["id","group_id","plan_id","start_time","end_time","code_type","percent","limit_percent_price","price","description","created_at","updated_at",], $requestParams);

        $query = DiscountCodeFeatures::query();
        $data = SmallHelper::fetchList($requestParams, $query, $request, $page, $limit, $orderColumn, $orderBy);
        self::assertTrue($data['resultStats']);
        self::assertEquals(200,$data['statusCode']);



        //// has data and get all with filter that has no operation

        $request = new Request(['page' => 0 , 'limit' => 300,'id' => '1,6' ,'op_id' => 'between'], $_GET, [], [], [], []);
        $request->headers->set('Content-Type', 'application/json');

        [$page, $limit] = SmallHelper::paginationParams($request);
        [$orderColumn, $orderBy] = SmallHelper::orderParams($request);

        $requestParams = (new DiscountCodeFeatures())->getParams();
        $query = DiscountCodeFeatures::query();
        $data = SmallHelper::fetchList($requestParams, $query, $request, $page, $limit, $orderColumn, $orderBy);

        self::assertTrue($data['resultStats']);
        self::assertEquals(200,$data['statusCode']);



        $request = new Request(['page' => 0 , 'limit' => 300,'message'=>'@this@' ,'op_message'=> 'like'], $_GET, [], [], [], []);
        $request->headers->set('Content-Type', 'application/json');

        [$page, $limit] = SmallHelper::paginationParams($request);
        [$orderColumn, $orderBy] = SmallHelper::orderParams($request);

        $requestParams = (new DiscountCodeFeatures())->getParams();
        $query = DiscountCodeFeatures::query();
        $data = SmallHelper::fetchList($requestParams, $query, $request, $page, $limit, $orderColumn, $orderBy);
        self::assertTrue($data['resultStats']);
        self::assertEquals(200,$data['statusCode']);
        self::assertEquals(1,$data['body'][0]['id']);
        self::assertNull($data['body'][1]);
    }
}