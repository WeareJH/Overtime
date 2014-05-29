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
     * @param int $id
     * @return \JhOvertime\Entity\Overtime|null
     */
    public function findOneByUserAndId(UserInterface $user, $id);

    /**
     * @param array $criteria
     * @param array $dateRange
     * @return \JhOvertime\Entity\Overtime[]
     */
    public function findByCriteriaAndDateRange(array $criteria, array $dateRange = null);
}
