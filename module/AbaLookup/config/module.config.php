<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'doctrine' => array(
		'driver' => array(
			'aba_lookup_annotation_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(realpath(__DIR__ . '/../src/AbaLookup/Entity/'))
			),
			'orm_default' => array(
				'drivers' => array(
					'AbaLookup\Entity' => 'aba_lookup_annotation_driver'
				)
			)
		)
	),
	'controllers' => array(
		'invokables' => array(
			'AbaLookup\Controller\Home'  => 'AbaLookup\Controller\HomeController',
			'AbaLookup\Controller\Users' => 'AbaLookup\Controller\UsersController',
		),
	),
	'router' => array(
		'routes' => array(
			'home' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'index',
					),
				),
			),
			'about' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/about',
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'about',
					),
				),
			),
			'privacy' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/privacy',
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'privacy'
					),
				),
			),
			'terms' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/terms',
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'terms',
					),
				),
			),
			'users' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'       => '/users/:id/:action',
					'constraints' => array(
						'id'     => '[0-9]*',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\Users',
						'action'     => 'profile',
					),
				),
			),
			'auth' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'       => '/users/:action',
					'constraints' => array(
						'action' => '(login)|(logout)|(register)',
					),
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\Users',
						'action'     => 'login',
					),
				),
			),
		),
	),
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map' => array(
			// layouts
			'layout/layout'     => __DIR__ . '/../view/layout/layout.phtml',
			'layout/home'       => __DIR__ . '/../view/layout/home.phtml',
			'layout/logged-out' => __DIR__ . '/../view/layout/logged-out.phtml',
			// error pages
			'error/404'   => __DIR__ . '/../view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
);
