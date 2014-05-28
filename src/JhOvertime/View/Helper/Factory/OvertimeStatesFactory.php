<?php

namespace JhOvertime\View\Helper\Factory;

use JhOvertime\View\Helper\OvertimeStates;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OvertimeStatesFactory
 * @package JhOvertime\Controller\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeStatesFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return OvertimeStates
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //get parent locator
        $serviceLocator = $serviceLocator->getServiceLocator();

        return new OvertimeStates(
            $serviceLocator->get('JhHub\ObjectManager')->getRepository('JhOvertime\Entity\OvertimeState'),
            $serviceLocator->get('Router'),
            $serviceLocator->get('Request')
        );
    }
}
