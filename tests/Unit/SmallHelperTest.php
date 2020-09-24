<?php

namespace Tests\Unit;

use App\Http\Helper\SmallHelper;
use Mockery;
use Tests\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class SmallHelperTest extends TestCase
{

    // check if string change code to uppercase correctly
    public function testChangeCodeToUppercase(): void
    {
        $result = (new SmallHelper)->changeCodeToUppercase('meysa120m');
        self::assertNotNull($result);
        self::assertIsString($result);
        self::assertSame(mb_strtoupper($result, 'utf-8'), $result);
        self::assertNotSame(mb_strtolower($result, 'utf-8'), $result);

    }

    // check code generator
    public function testCodeGenerator(): void
    {
        $prefix = 'test_' ;
        $stringType = config('settings.generatorString.bothCharacter') ;
        $length = config('settings.automateCodeLength') ;
        $code = (new SmallHelper)->codeGenerator($prefix, $stringType, $length);
        self::assertIsString($code);
        self::assertNotNull($code);
        self::assertNotFalse( strpos($code, $prefix));
        self::assertEquals($length, strlen(str_replace($prefix, '', $code)));
        $mock = Mockery::mock(SmallHelper::class,'codeGenerator');
        $mock->shouldReceive('codeGenerator')->once()->andReturn(
            null
        );
        self::assertInstanceOf(SmallHelper::class, $mock);
        $code = $mock->codeGenerator($prefix, $stringType, $length);
        self::assertNull($code);
        Mockery::close();
    }

    public function testCheckTime(): void
    {
        // if date is between two date
        $dateBegin= '2020-09-22 10:25:38';
        $dateEnd = '2020-09-25 13:25:38';

        $inputDate= '2020-09-23 13:25:38';
        $code = (new SmallHelper)->checkDateInterval($inputDate, $dateBegin, $dateEnd);
        assertTrue($code);

        $dateBegin= '2020-09-22 10:25:38';
        $dateEnd = '2020-09-25 13:25:38';

        $inputDate= '2020-09-25 13:25:39';
        $code = (new SmallHelper)->checkDateInterval($inputDate, $dateBegin, $dateEnd);
        assertFalse($code);
    }

}
