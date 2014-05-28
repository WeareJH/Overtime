<?php

namespace JhOvertime\View\Helper\Factory;

use JhOvertime\View\Helper\Users;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UsersFactory
 * @package JhOvertime\Controller\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class UsersFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return OvertimeStates
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //get parent locator
        $serviceLocator = $serviceLocator->getServiceLocator();

        return new Users(
            $serviceLocator->get('JhUser\Repository\UserRepository'),
            $serviceLocator->get('Router'),
            $serviceLocator->get('Request')
        );
    }
}
