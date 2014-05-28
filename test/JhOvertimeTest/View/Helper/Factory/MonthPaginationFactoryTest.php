<?php

namespace JhFlexiTimeTest\Service\Factory;

use JhOvertime\View\Helper\Factory\MonthPaginationFactory;
use Zend\View\HelperPluginManager;

/**
 * Class MonthPaginationFactoryTest
 * @package JhFlexiTimeTest\Service\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MonthPaginationFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryProcessesWithoutErrors()
    {
        $routeMatch = $this->getMock('Zend\Mvc\Router\RouteMatch', [], [], '', false);
        $event = $this->getMock('\Zend\Mvc\MvcEvent');
        $event
            ->expects($this->once())
            ->method('getRouteMatch')
            ->will($this->returnValue($routeMatch));

        $application = $this->getMock('Zend\Mvc\Application', [], [], '', false);
        $application
            ->expects($this->once())
            ->method('getMvcEvent')
            ->will($this->returnValue($event));

        $serviceLocator   = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('Application')
            ->will($this->returnValue($application));


        $manager = new HelperPluginManager();
        $manager->setServiceLocator($serviceLocator);

        $factory = new MonthPaginationFactory();
        $this->assertInstanceOf('JhOvertime\View\Helper\MonthPagination', $factory->createService($manager));
    }
}
