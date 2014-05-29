<?php

namespace JhOvertimeTest\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use JhOvertime\Entity\Overtime;
use JhOvertime\Entity\OvertimeState;
use JhUser\Entity\User;

/**
 * Class MultipleOvertime
 * @package JhOvertimeTest\Fixture
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MultipleOvertime extends AbstractFixture
{
    /**
     * @var Overtime[]
     */
    protected $overtime;

    /**
     * {inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $overTime1 = new Overtime();
        $overTime2 = new Overtime();

        $overTime1->setDate(new \DateTime("1 April 2014"));
        $overTime2->setDate(new \DateTime("1 May 2014"));

        $this->overtime[] = $overTime1;
        $this->overtime[] = $overTime2;

        $state = new OvertimeState();
        $state->setState('some-state');

        $manager->persist($state);
        $manager->flush();

        $user = new User();

        $user
            ->setEmail('aydin@hotmail.co.uk')
            ->setPassword('password');

        $manager->persist($user);
        $manager->flush();

        foreach ($this->overtime as $overTime) {
            $overTime->setUser($user);
            $overTime->setState($state);
            $manager->persist($overTime);
        }

        $manager->flush();
    }

    /**
     * @return Overtime[]
     */
    public function getOvertime()
    {
        return $this->overtime;
    }
}
