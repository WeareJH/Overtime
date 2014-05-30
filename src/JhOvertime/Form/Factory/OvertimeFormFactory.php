<?php

namespace JhOvertime\Form\Factory;

use JhOvertime\Form\OvertimeFieldset;
use JhOvertime\Form\OvertimeForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OvertimeFormFactory
 * @package JhOvertime\Form\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeFormFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return OvertimeForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator      = $serviceLocator->getServiceLocator();
        $objectManager      = $parentLocator->get('JhOvertime\ObjectManager');
        $overtimeFieldset   = new OvertimeFieldset($objectManager);
        return new OvertimeForm($objectManager, $overtimeFieldset);
    }
}
