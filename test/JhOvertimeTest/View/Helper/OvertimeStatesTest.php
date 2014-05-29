<?php

namespace JhOvertimeTest\View\Helper;

use JhOvertime\View\Helper\OvertimeStates;
use JhOvertime\Entity\OvertimeState;
use ReflectionClass;

/**
 * Class OvertimeStatesTest
 * @package JhOvertimeTest\View\Helper
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeStatesTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;
    protected $repository;
    protected $renderer;

    public function setUp()
    {
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        $this->helper = new OvertimeStates($this->repository);
        $this->renderer = $this->getMock('Zend\View\Renderer\PhpRenderer', ['url']);
        $this->helper->setView($this->renderer);
    }

    public function testInvokeReturnsSelf()
    {
        $route = 'test';
        $this->assertSame($this->helper, $this->helper->__invoke($route));
    }

    public function testRenderFunction()
    {
        $state1 = new OvertimeState();
        $this->setId($state1, 1);
        $state1->setState('Paid');
        $state2 = new OvertimeState();
        $this->setId($state2, 2);
        $state2->setState('Unpaid');

        $route = 'test';

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([$state1, $state2]));

        $this->renderer
            ->expects($this->at(0))
            ->method('url')
            ->with($route, ['state' => null], true)
            ->will($this->returnValue('http://test/'));

        $this->renderer
            ->expects($this->at(1))
            ->method('url')
            ->with($route, ['state' => $state1->getId()], true)
            ->will($this->returnValue('http://test/'));

        $this->renderer
            ->expects($this->at(2))
            ->method('url')
            ->with($route, ['state' => $state2->getId()], true)
            ->will($this->returnValue('http://test/'));

        $output = $this->helper->__invoke($route)->render();
        $expected = '<ul><li><a href="http://test/">All</a></li><li>' .
            '<a href="http://test/">Paid</a></li><li><a href="http://test/">Unpaid</a></li></ul>';
        $this->assertEquals($expected, $output);
    }

    public function testSetUlFormat()
    {
        $newUlFormat = 'someString';
        $this->helper->setUlFormat($newUlFormat);

        $reflector = new \ReflectionObject($this->helper);
        $property = $reflector->getProperty('ulFormat');
        $property->setAccessible(true);
        $this->assertEquals($newUlFormat, $property->getValue($this->helper));
    }

    /**
     * @param OvertimeState $state
     * @param int $id
     */
    public function setId(OvertimeState $state, $id)
    {
        $reflector = new ReflectionClass($state);
        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($state, $id);
    }
}
