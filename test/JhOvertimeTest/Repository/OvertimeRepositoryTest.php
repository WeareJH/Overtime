<?php

namespace JhOvertimeTest\Repository;

use JhOvertime\Repository\OvertimeRepository;
use JhOvertimeTest\Fixture\SingleOvertime;
use JhOvertimeTest\Fixture\MultipleOvertime;
use JhOvertimeTest\Util\ServiceManagerFactory;

/**
 * Class OvertimeRepositoryTest
 * @package JhOvertimeTest\Repository
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeRepositoryTest extends \PHPUnit_Framework_TestCase
{

    protected $repository;
    protected $objectRepository;
    protected $fixtureExecutor;

    public function setUp()
    {
        $this->objectRepository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $sm = ServiceManagerFactory::getServiceManager();
        $this->repository = $sm->get('JhOvertime\Repository\OvertimeRepository');
        $this->fixtureExecutor = $sm->get('Doctrine\Common\DataFixtures\Executor\AbstractExecutor');
        $this->assertInstanceOf('JhOvertime\Repository\OvertimeRepository', $this->repository);
    }

    public function testFindByCriteriaAndDateRangeReturnsAllIfNoDateRange()
    {
        $overtimeFixture = new MultipleOvertime();
        $this->fixtureExecutor->execute(array($overtimeFixture));

        $result = $this->repository->findByCriteriaAndDateRange([], null);
        $this->assertCount(2, $result);
    }

    public function testFindByCriteriaAndDateRangeReturnsAllRecordsOfParticularState()
    {
        $overtimeFixture = new MultipleOvertime();
        $this->fixtureExecutor->execute(array($overtimeFixture));

        $stateId = $overtimeFixture->getOvertime()[0]->getState()->getId();

        $result = $this->repository->findByCriteriaAndDateRange(['state' => $stateId], null);
        $this->assertCount(2, $result);
    }

    public function testFindByCriteriaAndDateRangeReturnsOnlyRecordsInDateRange()
    {
        $overtimeFixture = new MultipleOvertime();
        $this->fixtureExecutor->execute(array($overtimeFixture));
        $result = $this->repository->findByCriteriaAndDateRange(
            [],
            [new \DateTime("1 May 2014"), new \DateTime("31 May 2014")]
        );

        $this->assertCount(1, $result);
    }

    public function testFindByUserAndIdReturnsOvertimeIfExists()
    {

        $overtimeFixture = new SingleOvertime();
        $this->fixtureExecutor->execute(array($overtimeFixture));

        $result = $this->repository->findOneByUserAndId(
            $overtimeFixture->getOvertime()->getUser(),
            $overtimeFixture->getOvertime()->getId()
        );
        $this->assertInstanceOf('JhOvertime\Entity\Overtime', $result);
        $this->assertSame($overtimeFixture->getOvertime()->getId(), $result->getId());
        $this->assertSame($overtimeFixture->getOvertime()->getUser()->getId(), $result->getUser()->getId());
    }

    public function testFindByUserAndIdReturnsNullIfOvertimeNotExists()
    {
        $overtimeFixture = new SingleOvertime();
        $this->fixtureExecutor->execute(array($overtimeFixture));

        $result = $this->repository->findOneByUserAndId($overtimeFixture->getOvertime()->getUser(), 10);
        $this->assertNull($result);
    }

    public function testFindByIdReturnsOvertimeIfExists()
    {

        $overtimeFixture = new SingleOvertime();
        $this->fixtureExecutor->execute(array($overtimeFixture));

        $result = $this->repository->find($overtimeFixture->getOvertime()->getId());
        $this->assertInstanceOf('JhOvertime\Entity\Overtime', $result);
        $this->assertSame($overtimeFixture->getOvertime()->getId(), $result->getId());
        $this->assertSame($overtimeFixture->getOvertime()->getUser()->getId(), $result->getUser()->getId());
    }

    public function testFindByIdReturnsNullIfOvertimeNotExists()
    {
        $result = $this->repository->find(1);
        $this->assertNull($result);
    }

    public function testFindByReturnsEmptyIfNonExist()
    {
        $this->assertEmpty($this->repository->findBy(array('state' => 'noy-getting-it')));
    }

    public function testFindAll()
    {
        $objectRepository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $objectRepository
            ->expects($this->once())
            ->method('findAll');

        $repository = new OvertimeRepository($objectRepository);
        $repository->findAll();
    }

    public function testFindOneBy()
    {
        $objectRepository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $args = [];
        $objectRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args);

        $repository = new OvertimeRepository($objectRepository);
        $repository->findOneBy($args);
    }

    public function testGetClassNameReturnsCorrectEntityClass()
    {
        $this->assertSame(
            'JhOvertime\Entity\Overtime',
            $this->repository->getClassName()
        );
    }
}
