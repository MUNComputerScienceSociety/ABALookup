<?php

return [
	'doctrine' => [
		'driver' => [
			'aba_lookup_annotation_driver' => [
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [realpath(__DIR__ . '/../src/AbaLookup/Entity/')],
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
				'type'    => 'Literal',
				'options' => [
					'route'    => '/',
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'index',
					],
				],
			],
			'about' => [
				'type'    => 'Literal',
				'options' => [
					'route'    => '/about',
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'about',
					],
				],
			],
			'privacy' => [
				'type'    => 'Literal',
				'options' => [
					'route'    => '/privacy',
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'privacy',
					],
				],
			],
			'terms' => [
				'type'    => 'Literal',
				'options' => [
					'route'    => '/terms',
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'terms',
					],
				],
			],
			'users' => [
				'type'    => 'Segment',
				'options' => [
					'route'       => '/users/:id/:action[/:mode]',
					'constraints' => [
						'id'     => '[0-9]*',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					],
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Users',
						'action'     => 'profile',
					],
				],
			],
			'auth' => [
				'type'    => 'Segment',
				'options' => [
					'route'       => '/users/:action',
					'constraints' => [
						'action' => '(login)|(logout)|(register)',
					],
					'defaults' => [
						'controller' => 'AbaLookup\Controller\Users',
						'action'     => 'login',
					],
				],
			],
		],
	],
	'view_manager' => [
		'display_not_found_reason' => TRUE,
		'display_exceptions'       => TRUE,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map' => [
			// layouts
			'layout/layout'     => __DIR__ . '/../view/layout/layout.phtml',
			'layout/home'       => __DIR__ . '/../view/layout/home.phtml',
			'layout/logged-out' => __DIR__ . '/../view/layout/logged-out.phtml',
			// templates
			'users/profile-edit' => __DIR__ . '/../view/aba-lookup/users/profile-edit.phtml',
			'widget/footer'      => __DIR__ . '/../view/aba-lookup/widget/footer.phtml',
			// error pages
			'error/404'   => __DIR__ . '/../view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/error/index.phtml',
		],
		'template_path_stack' => [
			__DIR__ . '/../view',
		],
	],
	// Placeholder for console routes
	'console' => array(
		'router' => array(
			'routes' => array(
			),
		),
	),
];
