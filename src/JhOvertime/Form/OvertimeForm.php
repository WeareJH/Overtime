<?php

namespace JhOvertime\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\FieldsetInterface;

/**
 * Class OvertimeForm
 * @package JhOvertime\Form
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class OvertimeForm extends Form
{

    public function __construct(
        ObjectManager $objectManager,
        FieldsetInterface $overtimeFieldset,
        $name = null,
        $options = []
    ) {
        parent::__construct($name, $options);

        $this->setHydrator(new DoctrineHydrator($objectManager, 'JhOvertime\Entity\Overtime'))
            ->setInputFilter(new InputFilter())
            ->setAttribute('method', 'post')
            ->setAttribute('class', 'form-horizontal')
            ->setAttribute('id', 'overtime-form');

        $overtimeFieldset->setUseAsBaseFieldset(true);
        $this->add($overtimeFieldset, ['name' => 'overtime']);

        $this->add([
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'id'    => 'submitbutton',
                'class' => 'btn btn-danger',
            ),
        ]);
    }
}
