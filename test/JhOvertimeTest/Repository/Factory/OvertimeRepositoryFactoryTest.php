<?php

namespace JhOvertimeTest\Repository\Factory;

use JhOvertime\Repository\Factory\OvertimeRepositoryFactory;

/**
 * Class OvertimeRepositoryFactoryTest
 * @package JhFlexiTimeTest\Repository\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeRepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryProcessesWithoutErrors()
    {
        $repository     = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $objectManager  = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $objectManager
            ->expects($this->once())
            ->method('getRepository')
            ->with('JhOvertime\Entity\Overtime')
            ->will($this->returnValue($repository));

        $serviceLocator   = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('JhOvertime\ObjectManager')
            ->will($this->returnValue($objectManager));

        $factory = new OvertimeRepositoryFactory();
        $this->assertInstanceOf('JhOvertime\Repository\OvertimeRepository', $factory->createService($serviceLocator));
    }
}
