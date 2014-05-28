<?php

namespace JhOvertime\Form;

use JhOvertime\Entity\Overtime;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Common\Persistence\ObjectManager;
use JhOvertime\Hydrator\Strategy\DateToStringStrategy;

class OvertimeFieldset extends Fieldset implements InputFilterProviderInterface
{
    /**
     * @param ObjectManager $objectManager
     * @param null $name
     * @param array $options
     */
    public function __construct(ObjectManager $objectManager, $name = null, $options = [])
    {
        parent::__construct($name, $options);

        $hydrator = new DoctrineHydrator($objectManager, 'JhOvertime\Entity\Overtime');
        $hydrator->addStrategy('date', new DateToStringStrategy());

        $this->setHydrator($hydrator)
            ->setObject(new Overtime());


        $this->add([
            'name'  => 'id',
            'type'  => 'hidden'
        ]);

        $this->add([
            'name'      => 'date',
            'type'      => 'text',
            'options'   => [
                'label' => 'Date',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label',
                ],
            ],
            'attributes' => [
                'class' => 'form-control input-sm',
            ],
        ]);

        $this->add([
            'name'      => 'duration',
            'type'      => 'text',
            'options'   => [
                'label' => 'Duration (Hours)',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label',
                ],
            ],
            'attributes' => [
                'class' => 'form-control input-sm',
            ],
        ]);

        /*$this->add(
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
        );*/

        $this->add([
            'name'      => 'notes',
            'type'      => 'text',
            'options'   => [
                'label' => 'Notes',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label',
                ],
            ],
            'attributes' => [
                'class' => 'form-control input-sm',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'date' => [
                'required'  => true,
                'filters'   => [
                    [
                        'name' => 'Utils\Filter\DateTimeFormatter',
                        'options'   => ['format' => 'd-m-Y'],
                    ],
                ],
                'validators' => [
                    [
                        'name'      => 'Date',
                        'options'   => ['format' => 'd-m-Y'],
                    ],
                ],
            ],
            'duration' => [
                'required'  => true,
                'filters'   => [
                    [
                        'name' => 'Utils\Filter\MultiplyBySixty',
                    ],
                ],
                'validators' => [
                    [
                        'name'      => 'GreaterThan',
                        'options'   => ['min' => 0],
                    ],
                    [
                        'name'      => 'Step',
                        //TODO: get this from config
                        'options'   => ['step' => 15],
                    ],
                ],
            ],
            'notes' => [
                'required'  => false,
                'filters'   => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 512,
                        ],
                    ],
                ],
            ],
            /*'state' => [
                'required' => false,
            ]*/
        ];
    }
} 