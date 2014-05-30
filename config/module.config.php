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
                            'route' => '/list[/state/:state][/from/:from][/to/:to]',
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
            'overtimeStates'    => 'JhOvertime\View\Helper\Factory\OvertimeStatesFactory',
            'users'             => 'JhOvertime\View\Helper\Factory\UsersFactory',
            'monthPagination'   => 'JhOvertime\View\Helper\Factory\MonthPaginationFactory',
        ],
    ],

    'navigation' => [
        'default' => [
            [
                'name'      => 'Overtime',
                'label'     => 'Overtime',
                'route'     => 'overtime',
                'resource'  => 'user-nav',
                'privilege' => 'view',
            ],
        ],

        'admin' => [
            'overtime' => [
                'label' => 'Overtime',
                'route' => 'zfcadmin/overtime',
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
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Route' => [
                //overtime
                ['route' => 'overtime',                 'roles' => ['user']],
                ['route' => 'overtime/list',            'roles' => ['user']],
                ['route' => 'overtime/edit',            'roles' => ['user']],
                ['route' => 'overtime/add',             'roles' => ['user']],
                ['route' => 'overtime/delete',          'roles' => ['user']],

                //overtime admin
                ['route' => 'zfcadmin/overtime',        'roles' => ['user']],
                ['route' => 'zfcadmin/overtime/list',   'roles' => ['user']],
                ['route' => 'zfcadmin/overtime/edit',   'roles' => ['user']],
                ['route' => 'zfcadmin/overtime/add',    'roles' => ['user']],
                ['route' => 'zfcadmin/overtime/delete', 'roles' => ['user']],
            ],
        ],
    ],
];
