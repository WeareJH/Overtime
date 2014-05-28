<?php

namespace JhOvertime\Service;

use JhOvertime\Entity\Overtime;
use Doctrine\Common\Persistence\ObjectManager;

class OvertimeService
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;


    protected $config;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager    = $objectManager;
        $this->config           = ['default-state' => 'Unpaid'];
    }

    /**
     * @param Overtime $overtime
     */
    public function save(Overtime $overtime)
    {
        if (!$overtime->getId()) {
            if(!$overtime->getState()) {
                $state = $this->objectManager->getRepository('JhOvertime\Entity\OvertimeState')->findOneBy(['state' => $this->config['default-state']]);
                $overtime->setState($state);
            }
            $this->objectManager->persist($overtime);
        }
        $this->objectManager->flush();
    }

    public function delete(Overtime $overtime)
    {
        $this->objectManager->remove($overtime);
        $this->objectManager->flush();
    }

} 