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
use ZfcRbac\Exception\UnauthorizedException;

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
        FormInterface $overtimeForm
    ) {
        $this->overtimeService      = $overtimeService;
        $this->overtimeRepository   = $overtimeRepository;
        $this->overtimeForm         = $overtimeForm;
    }

    /**
     * @param string $date
     * @param \DateTime $defaultReturn
     * @return \DateTime
     */
    public function validateDate($date, \DateTime $defaultReturn)
    {
        $validator  = new DateValidator(array('format' => 'd-m-Y'));
        if ($validator->isValid($date)) {
            return \DateTime::createFromFormat('d-m-Y', $date);
        }

        return $defaultReturn;
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
        $page       = (int) $this->params()->fromQuery('page', 1);
        $dateRange  = null;

        if ($state) {
            $criteria['state'] = $state;
        }

        //if there is a User ID present and current user
        //is allowed to view others records
        //add it to criteria
        if ($userId && $this->isGranted('overtime.readOthers')) {
            $criteria['user'] = $userId;

        //if no User ID present and current user
        //is not allowed to view others records
        //restrict records to current user
        } elseif (!$this->isGranted('overtime.readOthers')) {
            $user = $this->zfcUserAuthentication()->getIdentity();
            $criteria['user'] = $user;
        }

        if (!$this->params()->fromRoute('all', false)) {
            $from   = $this->validateDate($fromDate, new \DateTime("first day of this month 00:00:00"));
            $to     = $this->validateDate($toDate, new \DateTime("first day of next month 00:00:00"));
            $dateRange = [ $from, $to];
        }

        $overtime = $this->overtimeRepository->findByCriteriaAndDateRange($criteria, $dateRange);
        $overtime->setCurrentPageNumber($page)
            ->setItemCountPerPage(10);

        return new ViewModel([
            'overtime'  => $overtime,
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $request    = $this->getRequest();
        $user       = $this->zfcUserAuthentication()->getIdentity();

        if (!$this->isGranted('overtime.create')) {
            throw new UnauthorizedException("Not Allowed!");
        }

        if ($request->isPost()) {
            $overtime   = new Overtime();
            $this->overtimeForm->bind($overtime);
            $this->overtimeForm->setData($request->getPost());

            if ($this->overtimeForm->isValid()) {
                try {

                    //only set use if this form form does not have the user
                    //field
                    if (!$this->overtimeForm->get('overtime')->has('user')) {
                        $overtime->setUser($user);
                    }

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

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        //ignore user element - as this can't be changed here
        $this->overtimeForm->setValidationGroup([
            'overtime' => [
                'state',
                'date',
                'duration',
                'notes',
            ],
        ]);

        $request    = $this->getRequest();
        $id         = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute($this->listRoute);
        }

        try {
            $overtime = $this->overtimeRepository->find($id);
        } catch (\Exception $e) {
            //add error message
            return $this->redirect()->toRoute($this->listRoute);
        }

        if (!$this->isGranted('overtime.edit', $overtime)) {
            throw new UnauthorizedException("Not Allowed!");
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
            }
        }

        return new ViewModel([
            'overtime'  => $overtime,
            'form'      => $this->overtimeForm,
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute($this->listRoute);
        }

        try {
            $overtime = $this->overtimeRepository->find($id);
        } catch (\Exception $e) {
            //add error message
            return $this->redirect()->toRoute($this->listRoute);
        }

        if (!$this->isGranted('overtime.delete', $overtime)) {
            throw new UnauthorizedException("Not Allowed!");
        }

        $this->overtimeService->delete($overtime);
        return $this->redirect()->toRoute($this->listRoute);
    }
}
