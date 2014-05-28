<?php

namespace JhOvertime\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use ZfcUser\Entity\UserInterface;
use DateTime;
use JhOvertime\Entity\OvertimeState;

/**
 * Class Overtime
 * @package JhOvertime\Entity
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 *
 * @ORM\Entity
 * @ORM\Table(name="overtime")
 */
class Overtime implements JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="JhUser\Entity\User")
     */
    protected $user = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="date", nullable=false)
     */
    protected $date;

    /**
     * @var int
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $duration;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $notes = null;

    /**
     * @var OvertimeState
     *
     * @ORM\ManyToOne(targetEntity="JhOvertime\Entity\OvertimeState")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $state = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param \ZfcUser\Entity\UserInterface $user
     * @return \JhOvertime\Entity\Overtime
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \ZfcUser\Entity\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param DateTime $date
     * @return \JhOvertime\Entity\Overtime
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $duration
     * @return \JhOvertime\Entity\Overtime
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * Return the duration in hours
     * eg. 0.25, 3.75
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->duration / 60;
    }

    /**
     * @param string $notes
     * @return \JhOvertime\Entity\Overtime
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param OvertimeState $state
     * @return self
     */
    public function setState(OvertimeState $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return OvertimeState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'id'        => $this->id,
            'date'      => $this->date->format('d-m-Y'),
            'duration'  => $this->duration,
            'notes'     => $this->notes,
        );
    }

}
