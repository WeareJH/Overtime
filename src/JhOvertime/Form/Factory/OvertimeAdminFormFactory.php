<?php

namespace JhOvertime\Form\Factory;

use JhOvertime\Form\OvertimeAdminFieldset;
use JhOvertime\Form\OvertimeForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OvertimeAdminFormFactory
 * @package JhOvertime\Form\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeAdminFormFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return OvertimeForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator      = $serviceLocator->getServiceLocator();
        $objectManager      = $parentLocator->get('JhHub\ObjectManager');
        $overtimeFieldset   = new OvertimeAdminFieldset($objectManager);
        return new OvertimeForm($objectManager, $overtimeFieldset);
    }
}