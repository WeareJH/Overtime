<?php

namespace JhOvertimeTest\Controller\Factory;

use JhOvertime\Controller\Factory\OvertimeControllerFactory;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Class OvertimeControllerFactoryTest
 * @package JhOvertime\Controller\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryProcessesWithoutErrors()
    {

        $overtimeService = $this
            ->getMockBuilder('JhOvertime\Service\OvertimeService')
            ->disableOriginalConstructor()
            ->getMock();

        $overtimeRepository = $this->getMock('JhOvertime\Repository\OvertimeRepositoryInterface');

        $formElementManager = new ServiceManager();
        $formElementManager->setService('JhOvertime\Form\OvertimeForm', $this->getMock('Zend\Form\FormInterface'));

        $services = array(
            'JhOvertime\Service\OvertimeService'        => $overtimeService,
            'JhOvertime\Repository\OvertimeRepository'  => $overtimeRepository,
            'FormElementManager'                        => $formElementManager,
        );

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator
            ->expects($this->any())
            ->method('get')
            ->will(
                $this->returnCallback(
                    function ($serviceName) use ($services) {
                        return $services[$serviceName];
                    }
                )
            );

        $controllerPluginManager = new PluginManager();
        $controllerPluginManager->setServiceLocator($serviceLocator);

        $factory = new OvertimeControllerFactory();
        $this->assertInstanceOf(
            'JhOvertime\Controller\OvertimeController',
            $factory->createService($controllerPluginManager)
        );
    }
}
