<?php

namespace JhOvertime\Controller\Factory;

use JhOvertime\Controller\OvertimeAdminController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OvertimeAdminControllerFactory
 * @package JhOvertime\Controller\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeAdminControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //get parent locator
        $serviceLocator = $serviceLocator->getServiceLocator();

        return new OvertimeAdminController(
            $serviceLocator->get('JhOvertime\Service\OvertimeService'),
            $serviceLocator->get('JhOvertime\Repository\OvertimeRepository'),
            $serviceLocator->get('FormElementManager')->get('JhOvertime\Form\OvertimeAdminForm')
        );
    }
}
