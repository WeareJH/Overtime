<?php

namespace JhOvertimeTest\Service\Factory;

use JhOvertime\Service\Factory\OvertimeServiceFactory;

/**
 * Class OvertimeServiceFactoryTest
 * @package JhFlexiTimeTest\Service\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeServiceFactoryTest extends \PHPUnit_Framework_TestCase
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
            ->expects($this->exactly(2))
            ->method('get')
            ->with('JhOvertime\ObjectManager')
            ->will($this->returnValue($objectManager));

        $factory = new OvertimeServiceFactory();
        $this->assertInstanceOf('JhOvertime\Service\OvertimeService', $factory->createService($serviceLocator));
    }
}
