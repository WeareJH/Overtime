<?php

namespace JhOvertime\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Common\Persistence\ObjectManager;

class OvertimeAdminFieldset extends OvertimeFieldset
{
    /**
     * @param ObjectManager $objectManager
     * @param null $name
     * @param array $options
     */
    public function __construct(ObjectManager $objectManager, $name = null, $options = [])
    {

        parent::__construct($objectManager, $name, $options);

        //Add User Select
        $this->add(
            [
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'user',
                'options' => [
                    'label'          => 'User',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label',
                    ],
                    'object_manager' => $objectManager,
                    'target_class'   => 'Jhuser\Entity\User',
                    'property'       => 'displayName',
                ],
                'attributes' => [
                    'class' => 'form-control input-sm',
                ],
            ]
        );

        //Add State Select
        $this->add(
            [
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'state',
                'options' => [
                    'label'          => 'State',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label',
                    ],
                    'object_manager' => $objectManager,
                    'target_class'   => 'JhOvertime\Entity\OvertimeState',
                    'property'       => 'state',
                ],
                'attributes' => [
                    'class' => 'form-control input-sm',
                ],
            ]
        );
    }
}
