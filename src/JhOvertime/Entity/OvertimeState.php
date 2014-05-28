<?php

namespace JhOvertime\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use ZfcUser\Entity\UserInterface;
use DateTime;

/**
 * Class Overtime
 * @package JhOvertime\Entity
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 *
 * @ORM\Entity
 * @ORM\Table(name="overtime_state")
 */
class OvertimeState
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true,  length=255, nullable=false)
     */
    protected $state;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
