<?php

namespace JhOvertime\View\Helper\Factory;

use JhOvertime\View\Helper\ParamEnabled;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ParamEnabledFactory
 * @package JhOvertime\View\Helper\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ParamEnabledFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return OvertimeStates
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //get parent locator
        $serviceLocator = $serviceLocator->getServiceLocator();

        return new ParamEnabled(
            $serviceLocator->get('Router'),
            $serviceLocator->get('Request')
        );
    }
}
