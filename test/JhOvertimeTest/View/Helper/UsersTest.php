<?php

namespace JhFlexiTimeTest\View\Helper;

use JhOvertime\View\Helper\Users;
use Zend\View\Renderer\PhpRenderer;
use ZfcUser\Entity\User;

/**
 * Class BookingClassesTest
 * @package JhFlexiTimeTest\View\Helper
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class BookingClassesTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;
    protected $repository;
    protected $renderer;

    public function setUp()
    {
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        $this->helper = new Users($this->repository);
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
        $user1 = new User();
        $user1->setId(1)->setDisplayName('Aydin');
        $user2 = new User();
        $user2->setId(2)->setDisplayName('Jack');

        $route = 'test';

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([$user1, $user2]));

        $this->renderer
            ->expects($this->at(0))
            ->method('url')
            ->with($route, ['user' => 'all'], true)
            ->will($this->returnValue('http://test/'));

        $this->renderer
            ->expects($this->at(1))
            ->method('url')
            ->with($route, ['user' => $user1->getId()], true)
            ->will($this->returnValue('http://test/'));

        $this->renderer
            ->expects($this->at(2))
            ->method('url')
            ->with($route, ['user' => $user2->getId()], true)
            ->will($this->returnValue('http://test/'));

        $output = $this->helper->__invoke($route)->render();
        $expected = '<ul><li><a href="http://test/">All</a></li><li>' .
            '<a href="http://test/">Aydin</a></li><li><a href="http://test/">Jack</a></li></ul>';
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
}
