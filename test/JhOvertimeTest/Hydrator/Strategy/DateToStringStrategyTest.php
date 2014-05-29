<?php

namespace JhOvertimeTest\Hydrator\Strategy;

use JhOvertime\Hydrator\Strategy\DateToStringStrategy;

/**
 * Class DateToStringStrategyTest
 * @package JhOvertimeTest\Hydrator\Strategy
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class DateToStringStrategyTest extends \PHPUnit_Framework_TestCase
{
    protected $strategy;

    public function setUp()
    {
        $this->strategy = new DateToStringStrategy();
    }

    public function testNullExtractsNull()
    {
        $this->assertNull($this->strategy->extract(null));
    }

    public function testNonNullNonDateThrowsException()
    {
        $this->setExpectedException("InvalidArgumentException", 'Field "stdClass" is not a valid DateTime object');
        $this->strategy->extract(new \stdClass());
    }

    public function testDateTimeIsFormatted()
    {
        $this->assertEquals('2014-05-30', $this->strategy->extract(new \DateTime("30 May 2014")));
    }

    public function testHydrateReturnsSame()
    {
        $obj = new \stdClass();
        $this->assertSame($obj, $this->strategy->hydrate($obj));
    }
}
