<?php

namespace JhOvertime\Controller;

use Zend\View\Model\ViewModel;
use Zend\Form\FormInterface;

/**
 * Class OvertimeController
 * @package JhOvertime\Controller
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeAdminController extends OvertimeController
{
    /**
     * @var string
     */
    protected $listRoute = 'zfcadmin/overtime';

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $this->overtimeForm->setValidationGroup([
            'overtime' => [
                'state',
                'date',
                'duration',
                'notes',
            ],
        ]);

        return parent::editAction();
    }

    /**
     * @return array|ViewModel
     */
    public function listAction()
    {
        $criteria   = [];
        $state      = $this->params()->fromRoute('state', false);
        $userId     = $this->params()->fromRoute('user', false);
        $toDate     = $this->params()->fromRoute('to');
        $fromDate   = $this->params()->fromRoute('from');
        $dateRange  = null;

        if ($state) {
            $criteria['state'] = $state;
        }

        if ($userId) {
            $user = $userId;
        } else {
            $user = $this->zfcUserAuthentication()->getIdentity();
        }

        if (!$this->params()->fromRoute('all', false)) {
            $from   = $this->validateDate($fromDate, new \DateTime("first day of this month"));
            $to     = $this->validateDate($toDate, new \DateTime("first day of next month"));
            $dateRange = [ $from, $to];
        }

        return new ViewModel([
            'user'      => $user,
            'overtime'  => $this->overtimeRepository->findByUserAndCriteriaAndDateRange($user, $criteria, $dateRange),
        ]);
    }
}
