<?php

return [
	'doctrine' => [
		'driver' => [
			'aba_lookup_annotation_driver' => [
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [
					realpath(__DIR__ . '/../src/AbaLookup/Entity/'),
				],
			],
			'orm_default' => [
				'drivers' => [
					'AbaLookup\Entity' => 'aba_lookup_annotation_driver',
				],
			],
		],
	],
	'controllers' => [
		'invokables' => [
			'AbaLookup\Controller\Home'  => 'AbaLookup\Controller\HomeController',
			'AbaLookup\Controller\Users' => 'AbaLookup\Controller\UsersController',
		],
	],
	'router' => [
		'routes' => [
			'home' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/',
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Home',
						'action' => 'index',
					],
				],
			],
			'privacy' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/privacy',
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Home',
						'action' => 'privacy',
					],
				],
			],
			'terms' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/terms',
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Home',
						'action' => 'terms',
					],
				],
			],
			'about' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/about',
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Home',
						'action' => 'about',
					],
				],
				'may_terminate' => TRUE,
				'child_routes' => [
					'sponsors' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/sponsors',
							'defaults' => [
								'action' => 'sponsors',
							],
						],
					],
					'colophon' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/colophon',
							'defaults' => [
								'action' => 'colophon',
							],
						],
					],
				],
			],
			'users' => [
				'type' => 'Segment',
				'options' => [
					'route' => '/users/:id/:action[/:mode]',
					'constraints' => [
						'id' => '[0-9]*',
						'action' => '[a-zA-Z0-9_-]+',
					],
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Users',
						'action' => 'profile',
					],
				],
			],
			'auth' => [
				'type' => 'Segment',
				'options' => [
					'route' => '/users/:action',
					'constraints' => [
						'action' => '(login)|(logout)|(register)',
					],
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Users',
						'action' => 'login',
					],
				],
			],
		],
	],
	'view_manager' => [
		'display_exceptions' => TRUE,
		'display_not_found_reason' => TRUE,
		'doctype' => 'HTML5',
		'exception_template' => 'error/index',
		'not_found_template' => 'error/404',
		'template_map' => [
			'error/404' => realpath(__DIR__ . '/../view/error/404.phtml'),
			'error/index' => realpath(__DIR__ . '/../view/error/index.phtml'),
			'layout/home' => realpath(__DIR__ . '/../view/layout/home.phtml'),
			'layout/layout' => realpath(__DIR__ . '/../view/layout/layout.phtml'),
			'users/profile-edit' => realpath(__DIR__ . '/../view/aba-lookup/users/profile-edit.phtml'),
			'widget/footer' => realpath(__DIR__ . '/../view/aba-lookup/widget/footer.phtml'),
		],
		'template_path_stack' => [
			realpath(__DIR__ . '/../view'),
		],
	],
];
