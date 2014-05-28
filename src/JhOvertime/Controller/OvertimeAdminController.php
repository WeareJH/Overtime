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
}
