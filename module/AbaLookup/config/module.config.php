<?php

return [
	'controllers' => [
		'invokables' => [
			'Home'  => 'AbaLookup\HomeController',
			'Users' => 'AbaLookup\UsersController',
		],
	],
	'router' => [
		'routes' => [
			'home' => [
				'type'    => 'Literal',
				'options' => [
					'route'    => '/',
					'defaults' => [
						'controller' => 'Home',
						'action'     => 'index',
					],
				],
			],
			'privacy' => [
				'type'    => 'Literal',
				'options' => [
					'route'    => '/privacy',
					'defaults' => [
						'controller' => 'Home',
						'action'     => 'privacy',
					],
				],
			],
			'terms' => [
				'type'    => 'Literal',
				'options' => [
					'route'    => '/terms',
					'defaults' => [
						'controller' => 'Home',
						'action'     => 'terms',
					],
				],
			],
			'about' => [
				'type'    => 'Literal',
				'options' => [
					'route'    => '/about',
					'defaults' => [
						'controller' => 'Home',
						'action'     => 'about',
					],
				],
				'may_terminate' => TRUE,
				'child_routes'  => [
					'sponsors' => [
						'type'    => 'Literal',
						'options' => [
							'route'    => '/sponsors',
							'defaults' => [
								'action' => 'sponsors',
							],
						],
					],
					'colophon' => [
						'type'    => 'Literal',
						'options' => [
							'route'    => '/colophon',
							'defaults' => [
								'action' => 'colophon',
							],
						],
					],
				],
			],
			'users' => [
				'type'    => 'Segment',
				'options' => [
					'route'       => '/users/:id/:action[/:mode]',
					'constraints' => [
						'id'     => '[0-9]*',
						'action' => '[a-zA-Z0-9_-]+',
					],
					'defaults' => [
						'controller' => 'Users',
						'action'     => 'profile',
					],
				],
			],
			'auth' => [
				'type'    => 'Literal',
				'options' => [
					'route' => '/users',
					'defaults' => [
						'controller' => 'Users',
					],
				],
				'may_terminate' => FALSE,
				'child_routes'  => [
					'register' => [
						'type'    => 'Segment',
						'options' => [
							'route'    => '/register/:type',
							'defaults' => [
								'action' => 'register',
							],
							'constraints' => [
								'type' => '[a-z]+',
							],
						],
					],
					'login' => [
						'type'    => 'Literal',
						'options' => [
							'route'    => '/login',
							'defaults' => [
								'action' => 'login',
							],
						],
					],
					'logout' => [
						'type'    => 'Literal',
						'options' => [
							'route'    => '/logout',
							'defaults' => [
								'action' => 'logout',
							],
						],
					],
				],
			],
		],
	],
	'view_manager' => [
		'display_exceptions'       => TRUE,
		'display_not_found_reason' => TRUE,
		'doctype'                  => 'HTML5',
		'exception_template'       => 'error/index',
		'not_found_template'       => 'error/404',
		'template_path_stack'      => [
			realpath(sprintf('%s/../view', __DIR__)),
		],
		'template_map' => [
			'layout/layout' => realpath(sprintf('%s/../view/layout/layout.phtml', __DIR__)),
			'layout/home'   => realpath(sprintf('%s/../view/layout/home.phtml',   __DIR__)),
			'error/index'   => realpath(sprintf('%s/../view/error/index.phtml',   __DIR__)),
			'error/404'     => realpath(sprintf('%s/../view/error/404.phtml',     __DIR__)),
			'profile/edit'  => realpath(sprintf('%s/../view/aba-lookup/users/profile-edit.phtml', __DIR__)),
			'widget/footer' => realpath(sprintf('%s/../view/aba-lookup/widget/footer.phtml', __DIR__)),
		],
	],
];
