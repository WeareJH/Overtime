<?php

namespace JhOvertime\Controller\Factory;

use JhOvertime\Controller\OvertimeController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OvertimeControllerFactory
 * @package JhOvertime\Controller\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //get parent locator
        $serviceLocator = $serviceLocator->getServiceLocator();

        return new OvertimeController(
            $serviceLocator->get('JhOvertime\Service\OvertimeService'),
            $serviceLocator->get('JhOvertime\Repository\OvertimeRepository'),
            $serviceLocator->get('FormElementManager')->get('JhOvertime\Form\OvertimeForm')
        );
    }
}
