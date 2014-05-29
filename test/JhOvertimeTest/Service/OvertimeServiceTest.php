<?php

namespace JhOvertimeTest\Service;

use JhOvertime\Entity\Overtime;
use JhOvertime\Entity\OvertimeState;
use JhOvertime\Service\OvertimeService;
use ReflectionClass;

/**
 * Class OvertimeServiceTest
 * @package JhOvertimeTest\Service
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $objectManager;
    protected $stateRepository;
    protected $service;

    public function setUp()
    {
        $this->objectManager    = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->stateRepository  = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        $this->service = new OvertimeService(
            $this->objectManager,
            $this->stateRepository
        );
    }

    /**
     * @param Overtime $overtime
     * @param int $id
     */
    public function setId(Overtime $overtime, $id)
    {
        $reflector = new ReflectionClass($overtime);
        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($overtime, $id);
    }

    public function testSaveExistingOvertimeEntityDoesNotAlterState()
    {
        $overtime = new Overtime();
        $this->setId($overtime, 4);
        $state = new OvertimeState();
        $overtime->setState($state);

        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->service->save($overtime);
        $this->assertSame($state, $overtime->getState());
    }

    public function testCreateNewOvertimeAddsDefaultStateIfNotExplicityAdded()
    {
        $overtime   = new Overtime();
        $state      = new OvertimeState();

        $this->stateRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['state' => 'Unpaid'])
            ->will($this->returnValue($state));

        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($overtime);

        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->service->save($overtime);
        $this->assertSame($state, $overtime->getState());
    }

    public function testCreateNewOvertimeDoesNotSetStateIfStateIfAlreadySet()
    {
        $overtime   = new Overtime();
        $state      = new OvertimeState();

        $overtime->setState($state);

        $this->stateRepository
            ->expects($this->never())
            ->method('findOneBy');

        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($overtime);

        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->service->save($overtime);
        $this->assertSame($state, $overtime->getState());
    }


    public function testDelete()
    {
        $overtime = new Overtime();

        $this->objectManager
            ->expects($this->once())
            ->method('remove')
            ->with($overtime);

        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->service->delete($overtime);
    }
}
