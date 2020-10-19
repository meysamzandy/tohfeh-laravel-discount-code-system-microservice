<?php

namespace Tests\Unit;

use App\Models\SuccessJobs;
use Tests\TestCase;

class SuccessJobsTest extends TestCase
{

    public function testGetParams(): void
    {
        $params = (new SuccessJobs())->getParams();
        self::assertIsArray($params);
        self::assertNotNull($params);

    }
}
