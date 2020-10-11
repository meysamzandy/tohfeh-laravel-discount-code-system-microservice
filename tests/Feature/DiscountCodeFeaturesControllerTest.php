<?php

namespace Tests\Feature;

use App\Http\Controllers\DiscountCodeFeaturesController;
use App\Models\DiscountCodeFeatures;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DiscountCodeFeaturesControllerTest extends TestCase
{
    public const FEATURE_URL = 'api/admin/feature';

    public function testDestroy(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=FullSeeder');
        // control count of seeds
        $this->assertDatabaseCount('discount_code_groups', 1);
        $this->assertDatabaseCount('discount_codes', 10);
        $this->assertDatabaseCount('discount_code_features', 2);
        $this->assertDatabaseCount('user_access_limits', 10);
        $this->assertDatabaseCount('market_access_limits', 10);
        $this->assertDatabaseCount('usage_logs', 10);

        // check if wrong url
        $url = self::FEATURE_URL;
        $response = $this->delete($url);
        $response->assertStatus(405);


        // check if token doesn't exist
        $url = self::FEATURE_URL . '/' . 1;
        $response = $this->delete($url);
        $response->assertStatus(403);

        // check if id doesn't exist
        $url = self::FEATURE_URL .'/'. 3000;
        $this->withoutMiddleware();
        $response = $this->delete($url);
        $response->assertStatus(404);
        
        
        // delete first feature correctly and still there is 1 in the related group
        $feature = DiscountCodeFeatures::all();
        $url = self::FEATURE_URL .'/'. $feature[0]->id;
        $this->withoutMiddleware();
        $response = $this->delete($url);
        $response->assertStatus(204);
        $this->assertDatabaseCount('discount_code_groups', 1);
        $this->assertDatabaseCount('discount_codes', 10);
        // feature should be omitted
        $this->assertDatabaseCount('discount_code_features', 1);
        $this->assertDatabaseCount('user_access_limits', 10);
        $this->assertDatabaseCount('market_access_limits', 10);
        $this->assertDatabaseCount('usage_logs', 10);
        $this->assertDatabaseMissing('discount_code_features', ['id' => $feature[0]->id]);

        // delete second feature correctly and  there is no any feature in the related group
        $url = self::FEATURE_URL .'/'. $feature[1]->id;
        $this->withoutMiddleware();
        $response = $this->delete($url);
        $response->assertStatus(204);
        // group should be deleted
        $this->assertDatabaseCount('discount_code_groups', 0);
        // features should be deleted
        $this->assertDatabaseCount('discount_code_features', 0);
        $this->assertDatabaseMissing('discount_code_features', ['id' => $feature[1]->id]);

        //   get exception error
        Artisan::call('migrate:rollback');
        $url = self::FEATURE_URL .'/'. 1 ;
        $this->withoutMiddleware();
        $response = $this->delete($url);
        $response->assertStatus(417);

    }
}
