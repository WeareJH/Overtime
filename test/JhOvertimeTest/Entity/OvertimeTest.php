<?php

namespace JhOvertimeTest\Entity;

use JhOvertime\Entity\Overtime;
use JhOvertime\Entity\OvertimeState;
use ReflectionClass;

/**
 * Class OvertimeTest
 * @package JhOvertimeTest\Entity
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeTest extends \PHPUnit_Framework_TestCase
{
    protected $overtime;

    public function setUp()
    {
        $this->overtime = new Overtime();
    }

    /**
     * @param Overtime $overtime
     * @param int $id
     */
    public function setId(Overtime $overtime, $id)
    {
        $reflector = new ReflectionClass($overtime);
        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($overtime, $id);
    }

    public function testId()
    {
        $this->assertNull($this->overtime->getId());
        $this->setId($this->overtime, 6);
        $this->assertEquals(6, $this->overtime->getId());
    }

    public function testSetGetUser()
    {
        $user = $this->getMock('ZfcUser\Entity\UserInterface');

        $this->assertNull($this->overtime->getUser());
        $this->overtime->setUser($user);
        $this->assertSame($user, $this->overtime->getUser());
    }

    public function testDateSetterGetter()
    {
        $date = new \DateTime();
        $this->assertNull($this->overtime->getDate());
        $this->overtime->setDate($date);
        $this->assertSame($date, $this->overtime->getDate());
    }

    public function testState()
    {
        $state = new OvertimeState();
        $this->assertNull($this->overtime->getState());
        $this->overtime->setState($state);
        $this->assertSame($state, $this->overtime->getState());
    }

    /**
     * Test the other setter/getters which have no default values
     *
     * @param string $name
     * @param mixed $value
     * @param mixed $defaultValue
     *
     * @dataProvider setterGetterProvider
     */
    public function testSetterGetter($name, $value, $defaultValue)
    {
        $getMethod = 'get' . ucfirst($name);
        $setMethod = 'set' . ucfirst($name);

        $this->assertEquals($defaultValue, $this->overtime->$getMethod());
        $this->overtime->$setMethod($value);
        $this->assertSame($value, $this->overtime->$getMethod());
    }

    /**
     * @return array
     */
    public function setterGetterProvider()
    {
        return [
            [
                'notes',
                'Why You even Unit Testing bro?',
                null
            ],
        ];
    }

    public function testDurationReturnsMinutesInHours()
    {
        $duration = 15; //15 minutes
        $this->overtime->setDuration($duration);
        $this->assertEquals(0.25, $this->overtime->getDuration());
    }

    public function testJsonSerialize()
    {
        $this->overtime->setDate(new \DateTime("12 May 2014"));

        $expected = [
            'id'        => null,
            'date'      => '12-05-2014',
            'duration'  => 0,
            'notes'     => null,
        ];

        $this->assertEquals($expected, $this->overtime->jsonSerialize());
    }

}
