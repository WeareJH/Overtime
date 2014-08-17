<?php

namespace JhOvertime\Assertion;

use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;
use JhOvertime\Entity\Overtime;

/**
 * Class MustBeOwnerToDeleteAssertion
 * @package JhOvertime\Assertion
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MustBeOwnerToDeleteAssertion implements AssertionInterface
{
    /**
     * @param AuthorizationService $authService
     * @param Overtime $overtime
     * @return bool
     */
    public function assert(AuthorizationService $authService, Overtime $overtime = null)
    {
        if ($authService->getIdentity() === $overtime->getUser()) {
            return true;
        }

        return $authService->isGranted('overtime.deleteOthers');
    }

}