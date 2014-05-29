<?php

namespace JhOvertime\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use JhOvertime\Entity\Overtime;
use Doctrine\Common\Persistence\ObjectManager;

class OvertimeService
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var ObjectRepository
     */
    protected $stateRepository;

    /**
     * @param ObjectManager $objectManager
     * @param ObjectRepository $stateRepository
     */
    public function __construct(ObjectManager $objectManager, ObjectRepository $stateRepository)
    {
        $this->objectManager    = $objectManager;
        $this->stateRepository  = $stateRepository;
        $this->config           = ['default-state' => 'Unpaid'];
    }

    /**
     * TODO: Just inject the default state
     *
     * @param Overtime $overtime
     */
    public function save(Overtime $overtime)
    {
        if (!$overtime->getId()) {
            if (!$overtime->getState()) {
                $state = $this->stateRepository->findOneBy(['state' => $this->config['default-state']]);
                $overtime->setState($state);
            }
            $this->objectManager->persist($overtime);
        }
        $this->objectManager->flush();
    }

    /**
     * @param Overtime $overtime
     */
    public function delete(Overtime $overtime)
    {
        $this->objectManager->remove($overtime);
        $this->objectManager->flush();
    }
}
