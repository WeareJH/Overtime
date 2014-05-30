<?php

namespace JhFlexiTimeTest\View\Helper\Factory;

use JhOvertime\View\Helper\Factory\UsersFactory;
use Zend\View\HelperPluginManager;

/**
 * Class UsersFactoryTest
 * @package JhFlexiTimeTest\Service\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class UsersFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryProcessesWithoutErrors()
    {
        $repository     = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('JhUser\Repository\UserRepository')
            ->will($this->returnValue($repository));


        $manager = new HelperPluginManager();
        $manager->setServiceLocator($serviceLocator);

        $factory = new UsersFactory();
        $this->assertInstanceOf('JhOvertime\View\Helper\Users', $factory->createService($manager));
    }
}
