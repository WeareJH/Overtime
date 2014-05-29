<?php

namespace JhOvertimeTest\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use JhOvertime\Entity\Overtime;
use JhUser\Entity\User;
use JhOvertime\Entity\OvertimeState;

/**
 * Class SingleOvertime
 * @package JhOvertimeTest\Fixture
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class SingleOvertime extends AbstractFixture
{
    /**
     * @var Overtime
     */
    protected $overtime;

    /**
     * {inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
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

        $this->overtime = new Overtime();
        $this->overtime->setUser($user);
        $this->overtime->setState($state);
        $this->overtime->setDate(new \DateTime);
        $manager->persist($this->overtime);
        $manager->flush();
    }

    /**
     * @return Overtime
     */
    public function getOvertime()
    {
        return $this->overtime;
    }
}
