<?php

namespace JhOvertimeTest\Form\Factory;

use JhOvertime\Form\Factory\OvertimeFormFactory;
use Zend\Form\FormElementManager;

/**
 * Class OvertimeFormFactoryTest
 * @package JhFlexiTimeTest\Form\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryProcessesWithoutErrors()
    {
        $services = [
            'JhOvertime\ObjectManager' => $this->getMock('Doctrine\Common\Persistence\ObjectManager'),
        ];

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

        $formElementManager = new FormElementManager();
        $formElementManager->setServiceLocator($serviceLocator);

        $factory = new OvertimeFormFactory();
        $this->assertInstanceOf('JhOvertime\Form\OvertimeForm', $factory->createService($formElementManager));
    }
}
