<?php

namespace JhOvertime\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use ZfcUser\Entity\UserInterface;

/**
 * Class OvertimeRepository
 * @package JhOvertime\Repository
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeRepository implements OvertimeRepositoryInterface, ObjectRepository
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $overtimeRepository;

    /**
     * @param ObjectRepository $overtimeRepository
     */
    public function __construct(ObjectRepository $overtimeRepository)
    {
        $this->overtimeRepository = $overtimeRepository;
    }

    /**
     * @param UserInterface $user
     * @return object
     */
    public function findByUser(UserInterface $user)
    {
        return $this->overtimeRepository->findBy(array('user' => $user));
    }

    public function findByUserAndCriteriaAndDateRange(UserInterface $user, array $criteria, array $dateRange = null)
    {
        $criteria['user'] = $user;

        $params = [];
        foreach($criteria as $field => $value) {
            $params[] = ['field' => $field, 'op' => '=', 'value' => $value];
        }

        $qb = $this->overtimeRepository->createQueryBuilder('o');
        $qb->select('o');

        if($dateRange) {
            $qb->andWhere('o.date >= :startDate');
            $qb->andWhere('o.date <= :endDate');

            $qb->setParameters([
                'startDate' => $dateRange[0],
                'endDate' => $dateRange[1],
            ]);
        }

        foreach($params as $param) {
            $qb->andWhere(sprintf('o.%s %s :%s', $param['field'], $param['op'], $param['field']));
            $qb->setParameter($param['field'], $param['value']);
        }

        $qb->orderBy('o.date', 'ASC');
        return $qb->getQuery()->getResult();
    }


    /**
     * @param UserInterface $user
     * @param int $id
     * @return \JhOvertime\Entity\Overtime|null
     */
    public function findOneByUserAndId(UserInterface $user, $id)
    {
        return $this->overtimeRepository->findOneBy(array('user' => $user, 'id' => $id));
    }

    /**
     * find(): defined by ObjectRepository.
     *
     * @see    ObjectRepository::find()
     * @param  int $id
     * @return UserInterface|null
     */
    public function find($id)
    {
        return $this->overtimeRepository->find($id);
    }

    /**
     * findAll(): defined by ObjectRepository.
     *
     * @see    ObjectRepository::findAll()
     * @return UserInterface[]
     */
    public function findAll()
    {
        return $this->overtimeRepository->findAll();
    }

    /**
     * findBy(): defined by ObjectRepository.
     *
     * @see    ObjectRepository::findBy()
     * @param  array      $criteria
     * @param  array|null $orderBy
     * @param  int|null   $limit
     * @param  int|null   $offset
     * @return UserInterface[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->overtimeRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * findOneBy(): defined by ObjectRepository.
     *
     * @see    ObjectRepository::findOneBy()
     * @param  array $criteria
     * @return UserInterface|null
     */
    public function findOneBy(array $criteria)
    {
        return $this->overtimeRepository->findOneBy($criteria);
    }

    /**
     * getClassName(): defined by ObjectRepository.
     *
     * @see    ObjectRepository::getClassName()
     * @return string
     */
    public function getClassName()
    {
        return $this->overtimeRepository->getClassName();
    }
}