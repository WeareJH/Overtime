<?php

namespace JhOvertime\Repository\Factory;

use JhOvertime\Repository\OvertimeRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OvertimeRepositoryFactory
 * @package JhOvertime\Repository\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeRepositoryFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \JhOvertime\Repository\OvertimeRepository
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OvertimeRepository(
            $serviceLocator->get('JhHub\ObjectManager')->getRepository('JhOvertime\Entity\Overtime')
        );
    }
}
