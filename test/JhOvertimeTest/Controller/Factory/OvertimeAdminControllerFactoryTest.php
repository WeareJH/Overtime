<?php

namespace JhOvertimeTest\Controller\Factory;

use JhOvertime\Controller\Factory\OvertimeAdminControllerFactory;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Class OvertimeAdminControllerFactoryTest
 * @package JhOvertimeTest\Controller\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeAdminControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryProcessesWithoutErrors()
    {

        $overtimeService = $this
            ->getMockBuilder('JhOvertime\Service\OvertimeService')
            ->disableOriginalConstructor()
            ->getMock();

        $overtimeRepository = $this->getMock('JhOvertime\Repository\OvertimeRepositoryInterface');

        $formElementManager = new ServiceManager();
        $formElementManager->setService('JhOvertime\Form\OvertimeAdminForm', $this->getMock('Zend\Form\FormInterface'));

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

        $factory = new OvertimeAdminControllerFactory();
        $this->assertInstanceOf(
            'JhOvertime\Controller\OvertimeAdminController',
            $factory->createService($controllerPluginManager)
        );
    }
}
