<?php

namespace JhOvertime\Repository;

use ZfcUser\Entity\UserInterface;

/**
 * Interface OvertimeRepositoryInterface
 * @package JhOvertime\Repository
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface OvertimeRepositoryInterface
{
    /**
     * @param UserInterface $user
     * @return \JhOvertime\Entity\Overtime[]
     */
    public function findByUser(UserInterface $user);

    /**
     * @param UserInterface $user
     * @param int $id
     * @return \JhOvertime\Entity\Overtime|null
     */
    public function findOneByUserAndId(UserInterface $user, $id);

    /**
     * @param UserInterface $user
     * @param array $criteria
     * @param array $dateRange
     * @return \JhOvertime\Entity\Overtime[]
     */
    public function findByUserAndCriteriaAndDateRange(UserInterface $user, array $criteria, array $dateRange = null);
}