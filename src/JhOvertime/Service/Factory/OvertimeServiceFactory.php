<?php

namespace JhOvertime\Service\Factory;

use JhOvertime\Service\OvertimeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OvertimeServiceFactory
 * @package JhOvertime\Service\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeServiceFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return OvertimeService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OvertimeService(
            $serviceLocator->get('JhHub\ObjectManager')
        );
    }
}
