<?php

namespace JhOvertime\View\Helper\Factory;

use JhOvertime\View\Helper\MonthPagination;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MonthPaginationFactory
 * @package JhOvertime\View\Helper\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MonthPaginationFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MonthPagination
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //get parent locator & MVC Event
        $serviceLocator = $serviceLocator->getServiceLocator();
        $event          = $serviceLocator->get('Application')->getMvcEvent();
        return new MonthPagination(
            $event->getRouteMatch()
        );
    }
}
