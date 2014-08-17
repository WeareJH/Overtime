<?php

namespace JhOvertime;

return [

    //entities
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ]
    ],

    //controllers
    'controllers' => [
        'factories' => [
            'JhOvertime\Controller\Overtime'       => 'JhOvertime\Controller\Factory\OvertimeControllerFactory',
            'JhOvertime\Controller\OvertimeAdmin'  => 'JhOvertime\Controller\Factory\OvertimeAdminControllerFactory',
        ],
    ],

    //service manager
    'service_manager' => [
        'factories' => [
            'JhOvertime\Repository\OvertimeRepository'  => 'JhOvertime\Repository\Factory\OvertimeRepositoryFactory',
            'JhOvertime\Service\OvertimeService'        => 'JhOvertime\Service\Factory\OvertimeServiceFactory',

        ],
        'aliases' => [
            'JhOvertime\ObjectManager' => 'Doctrine\ORM\EntityManager',
        ],
    ],

    //forms & fieldsets
    'form_elements' => [
        'factories' => [
            'JhOvertime\Form\OvertimeForm'      => 'JhOvertime\Form\Factory\OvertimeFormFactory',
            'JhOvertime\Form\OvertimeAdminForm' => 'JhOvertime\Form\Factory\OvertimeAdminFormFactory',
        ],
    ],

    //router
    'router' => [
        'routes' => [
            'overtime' => [
                'type'      => 'literal',
                'options'   => [
                    'route'     => '/overtime',
                    'defaults'  => [
                        'controller' => 'JhOvertime\Controller\Overtime',
                        'action'     => 'list',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'list' => [
                        'type'      => 'segment',
                        'options'   => [
                            'route' => '/list[/state/:state][/from/:from][/to/:to][/all/:all]',
                            'constraints' => [
                                'page' => '[0-9]+',
                            ],
                        ],
                    ],
                    'add' => [
                        'type'      => 'literal',
                        'options'   => [
                            'route'     => '/add',
                            'defaults'  => [
                                'controller' => 'JhOvertime\Controller\Overtime',
                                'action'     => 'add',
                            ],
                        ],
                    ],
                    'edit' => [
                        'type'      => 'segment',
                        'options'   => [
                            'route'     => '/edit/:id',
                            'defaults'  => [
                                'controller' => 'JhOvertime\Controller\Overtime',
                                'action'     => 'edit',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type'      => 'segment',
                        'options'   => [
                            'route'     => '/delete/:id',
                            'defaults'  => [
                                'controller' => 'JhOvertime\Controller\Overtime',
                                'action'     => 'delete',
                            ],
                        ],
                    ],
                ] ,
            ],
            //admin routes
            'zfcadmin' => [
                'child_routes' => [
                    'overtime' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/overtime',
                            'defaults' => [
                                'controller' => 'JhOverTime\Controller\OvertimeAdmin',
                                'action'     => 'list',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'list' => [
                                'type'      => 'segment',
                                'options'   => [
                                    'route' => '/list[/state/:state][/from/:from][/to/:to][/user/:user][/all/:all]',
                                ],
                            ],
                            'add' => [
                                'type'      => 'literal',
                                'options'   => [
                                    'route'     => '/add',
                                    'defaults'  => [
                                        'controller' => 'JhOvertime\Controller\OvertimeAdmin',
                                        'action'     => 'add',
                                    ],
                                ],
                            ],
                            'edit' => [
                                'type'      => 'segment',
                                'options'   => [
                                    'route'     => '/edit/:id',
                                    'defaults'  => [
                                        'controller' => 'JhOvertime\Controller\OvertimeAdmin',
                                        'action'     => 'edit',
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type'      => 'segment',
                                'options'   => [
                                    'route'     => '/delete/:id',
                                    'defaults'  => [
                                        'controller' => 'JhOvertime\Controller\OvertimeAdmin',
                                        'action'     => 'delete',
                                    ],
                                ],
                            ],
                        ] ,
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    //view helpers
    'view_helpers' => [
        'factories' => [
            'overtimeStates'        => 'JhOvertime\View\Helper\Factory\OvertimeStatesFactory',
            'users'                 => 'JhOvertime\View\Helper\Factory\UsersFactory',
            'monthPagination'       => 'JhOvertime\View\Helper\Factory\MonthPaginationFactory',
            'paramEnabled'          => 'JhOvertime\View\Helper\Factory\ParamEnabledFactory',
            'currentFilterParams'   => 'JhOvertime\View\Helper\Factory\CurrentFilterParamsFactory',
        ],
    ],

    'spiffy_navigation' => [
        'containers' => [
            'default' => [
                [
                    'options' => [
                        'name'          => 'Overtime',
                        'label'         => 'Overtime',
                        'route'         => 'overtime/list',
                        'role'          => 'user',
                        'permission'    => 'user-nav.view'
                    ],
                ],
            ],
            'admin' => [
                [
                    'options' => [
                        'name'          => 'Overtime Admin',
                        'label'         => 'Overtime',
                        'route'         => 'zfcadmin/overtime/list',
                    ],
                ]
            ],
        ],
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __DIR__ . '/../public',
            ],
        ],
    ],

    //route guards (ACL)
    'guards' => [
        'ZfcRbac\Guard\RouteGuard' => [
            'overtime*' => ['user'],
        ]
    ],

    'zfc_rbac' => [
        'assertion_map' => [
            'overtime.edit'     => 'JhOvertime\Assertion\MustBeOwnerToEditAssertion',
            'overtime.read'     => 'JhOvertime\Assertion\MustBeOwnerToReadAssertion',
            'overtime.delete'   => 'JhOvertime\Assertion\MustBeOwnerToDeleteAssertion',
        ]
    ],

    'jh_hub' => [
        'roles' => [
            'admin' => [
                'permissions' => [
                    'overtime.createOthers',
                    'overtime.editOthers',
                    'overtime.readOthers',
                    'overtime.deleteOthers',
                ],
                'children' => [
                    'user' => [
                        'permissions' => [
                            'overtime.create',
                            'overtime.edit',
                            'overtime.read',
                            'overtime.delete',
                        ],
                    ],
                ],
            ],
        ]
    ],
];
