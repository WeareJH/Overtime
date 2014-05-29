<?php

namespace JhOvertimeTest\Entity;

use JhOvertime\Entity\OvertimeState;
use ReflectionClass;

/**
 * Class OvertimeStateTest
 * @package JhOvertimeTest\Entity
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeStateTest extends \PHPUnit_Framework_TestCase
{
    protected $overtimeState;

    public function setUp()
    {
        $this->overtimeState = new OvertimeState();
    }

    /**
     * @param OvertimeState $overtimeState
     * @param int $id
     */
    public function setId(OvertimeState $overtimeState, $id)
    {
        $reflector = new ReflectionClass($overtimeState);
        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($overtimeState, $id);
    }

    public function testId()
    {
        $this->assertNull($this->overtimeState->getId());
        $this->setId($this->overtimeState, 7);
        $this->assertEquals(7, $this->overtimeState->getId());
    }

    public function testState()
    {
        $state = 'test';
        $this->overtimeState->setState($state);
        $this->assertEquals($state, $this->overtimeState->getState());
    }
}
