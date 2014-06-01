<?php

namespace JhOvertime\View\Helper\Factory;

use JhOvertime\View\Helper\CurrentFilterParams;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CurrentFilterParamsFactory
 * @package JhOvertime\View\Helper\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CurrentFilterParamsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return OvertimeStates
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //get parent locator
        $serviceLocator = $serviceLocator->getServiceLocator();

        return new CurrentFilterParams(
            $serviceLocator->get('Router'),
            $serviceLocator->get('Request'),
            $serviceLocator->get('JhOvertime\ObjectManager')->getRepository('JhOvertime\Entity\OvertimeState'),
            $serviceLocator->get('JhUser\Repository\UserRepository')
        );
    }
}
