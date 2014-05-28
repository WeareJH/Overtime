<?php

namespace JhFlexiTimeTest\Service\Factory;

use JhOvertime\View\Helper\Factory\OvertimeStatesFactory;
use Zend\View\HelperPluginManager;

/**
 * Class OvertimeStatesFactoryTest
 * @package JhFlexiTimeTest\Service\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeStatesFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryProcessesWithoutErrors()
    {
        $repository     = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $objectManager  = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $objectManager
            ->expects($this->once())
            ->method('getRepository')
            ->with('JhOvertime\Entity\OvertimeState')
            ->will($this->returnValue($repository));

        $serviceLocator   = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('JhHub\ObjectManager')
            ->will($this->returnValue($objectManager));


        $manager = new HelperPluginManager();
        $manager->setServiceLocator($serviceLocator);

        $factory = new OvertimeStatesFactory();
        $this->assertInstanceOf('JhOvertime\View\Helper\OvertimeStates', $factory->createService($manager));
    }
}
