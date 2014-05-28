<?php

namespace JhOvertime\Controller;

use JhOvertime\Entity\Overtime;
use JhOvertime\Repository\OvertimeRepositoryInterface;
use JhOvertime\Service\OvertimeService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\FormInterface;
use Zend\Validator\Date as DateValidator;
use ZfcDatagrid\Column;

/**
 * Class OvertimeController
 * @package JhOvertime\Controller
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeController extends AbstractActionController
{

    /**
     * @var OvertimeService
     */
    protected $overtimeService;

    /**
     * @var OvertimeRepositoryInterface
     */
    protected $overtimeRepository;

    /**
     * @var OvertimeForm
     */
    protected $overtimeForm;

    /**
     * @var string
     */
    protected $listRoute = 'overtime/list';

    /**
     * @param OvertimeService $overtimeService
     * @param OvertimeRepositoryInterface $overtimeRepository
     * @param FormInterface $overtimeForm
     */
    public function __construct(
        OvertimeService $overtimeService,
        OvertimeRepositoryInterface $overtimeRepository,
        FormInterface $overtimeForm)
    {
        $this->overtimeService      = $overtimeService;
        $this->overtimeRepository   = $overtimeRepository;
        $this->overtimeForm         = $overtimeForm;
    }

    public function generateDateRange() {

        $month  = (string) $this->params()->fromQuery('m');
        $year   = (string) $this->params()->fromQuery('y');

        $validator  = new DateValidator(array('format' => 'M Y'));
        if ($validator->isValid(sprintf("%s %s", $month, $year))) {
            return [
                new \DateTime(sprintf('first day of %s %s 00:00:00', $month, $year)),
                new \DateTime(sprintf('last day of %s %s 23:59:59', $month, $year)),
            ];
        } else {
            return null;
        }
    }

    public function validateDate($date, \DateTime $defaultReturn)
    {
        $validator  = new DateValidator(array('format' => 'd-m-Y'));
        if($validator->isValid($date) ) {
            return \DateTime::createFromFormat('d-m-Y', $date);
        }

        return $defaultReturn;
    }

    /**
     * @return array|ViewModel
     */
    public function listAction()
    {
        $state      = $this->params()->fromRoute('state', false);
        $userId     = $this->params()->fromRoute('user', false);
        $toDate     = $this->params()->fromRoute('to');
        $fromDate   = $this->params()->fromRoute('from');
        $dateRange  = null;
        $criteria   = [];

        if($state) {
            $criteria['state'] = $state;
        }

        if($userId) {
            $criteria['user'] = $userId;
        }

        if(!$toDate) {
            //no-date filtering, page and show all
        }

        if(!$this->params()->fromRoute('all', false)) {
            $from   = $this->validateDate($fromDate, new \DateTime("first day of this month"));
            $to     = $this->validateDate($toDate, new \DateTime("first day of next month"));
            $dateRange = [ $from, $to];
        }

        $user = $this->zfcUserAuthentication()->getIdentity();

        return new ViewModel([
            'user'      => $user,
            'overtime'  => $this->overtimeRepository->findByUserAndCriteriaAndDateRange($user, $criteria, $dateRange),
        ]);

    }

    public function addAction()
    {
        $request    = $this->getRequest();
        $user       = $this->zfcUserAuthentication()->getIdentity();

        if ($request->isPost()) {
            $overtime   = new Overtime();
            $this->overtimeForm->bind($overtime);
            $this->overtimeForm->setData($request->getPost());

            if ($this->overtimeForm->isValid()) {
                try {
                    $overtime->setUser($user);
                    $this->overtimeService->save($overtime);
                    //add messages
                    return $this->redirect()->toRoute($this->listRoute);
                } catch (\Exception $e) {
                    //add messages
                }
            }
        }

        return new ViewModel([
            'user'  => $user,
            'form'  => $this->overtimeForm,
        ]);
    }

    public function editAction()
    {
        $request    = $this->getRequest();
        $id         = $this->params()->fromRoute('id');
        $user       = $this->zfcUserAuthentication()->getIdentity();

        if(!$id) {
            return $this->redirect()->toRoute($this->listRoute);
        }

        try {
            $overtime = $this->overtimeRepository->findOneByUserAndId($this->zfcUserAuthentication()->getIdentity(), $id);
        } catch (\Exception $e) {
            //add error message
            return $this->redirect()->toRoute($this->listRoute);
        }

        $this->overtimeForm->bind($overtime);
        if ($request->isPost()) {
            $this->overtimeForm->setData($request->getPost());
            if ($this->overtimeForm->isValid()) {
                try {
                    $this->overtimeService->save($overtime);
                    //add messages
                    return $this->redirect()->toRoute($this->listRoute);
                } catch (\Exception $e) {
                    //add messages
                }
            } else {
                var_dump($this->overtimeForm->getMessages());
            }

        }

        return new ViewModel([
            'user'      => $user,
            'overtime'  => $overtime,
            'form'      => $this->overtimeForm,
        ]);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        if(!$id) {
            return $this->redirect()->toRoute($this->listRoute);
        }

        try {
            $overtime = $this->overtimeRepository->findOneByUserAndId($this->zfcUserAuthentication()->getIdentity(), $id);
        } catch (\Exception $e) {
            //add error message
            return $this->redirect()->toRoute($this->listRoute);
        }


        $this->overtimeService->delete($overtime);
        return $this->redirect()->toRoute($this->listRoute);
    }
}