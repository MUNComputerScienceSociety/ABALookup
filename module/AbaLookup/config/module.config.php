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
			'aba-lookup_annotation_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(realpath(__DIR__ . '/../src/AbaLookup/Entity/'))
			),
			'orm_default' => array(
				'drivers' => array(
					'AbaLookup\Entity' => 'aba-lookup_annotation_driver'
				)
			)
		)
	),
	'router' => array(
		'routes' => array(
			'home' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\Home',
						'action'     => 'index',
					),
				),
			),
			'about' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/about',
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\About',
						'action'     => 'index',
					),
				),
			),
			'home-index' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'controller'    => 'AbaLookup\Controller\Home',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '[:controller[/:action]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					)
				),
			),
			'user' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/user[/:action[/:id[/:verification]]]',
					'constraints' => array(
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\User',
						'action'     => 'login',
						'id'         => '',
						'verification' => ''
					)
				),
			),
			'schedule' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/schedule[/:action[/:id]]',
					'constraints' => array(
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\Schedule',
						'action'     => 'index',
						'id'         => ''
					)
				),
			),
			'parent-profile' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/parentprofile[/:action]',
					'constraints' => array(
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\ParentProfile',
						'action'     => 'index',
					)
				),
			),
			'therapist-profile' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/therapistprofile[/:action]',
					'constraints' => array(
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'AbaLookup\Controller\TherapistProfile',
						'action'     => 'index',
					)
				),
			),
		),
	),
	'service_manager' => array(
		'factories' => array(
			'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
		),
	),
	'translator' => array(
		'locale' => 'en_US',
		'translation_file_patterns' => array(
			array(
				'type'     => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.mo',
			),
		),
	),
	'controllers' => array(
		'invokables' => array(
			'AbaLookup\Controller\About'            => 'AbaLookup\Controller\AboutController',
			'AbaLookup\Controller\Admin'            => 'AbaLookup\Controller\AdminController',
			'AbaLookup\Controller\Home'             => 'AbaLookup\Controller\HomeController',
			'AbaLookup\Controller\Index'            => 'AbaLookup\Controller\IndexController',
			'AbaLookup\Controller\Match'            => 'AbaLookup\Controller\MatchController',
			'AbaLookup\Controller\ParentProfile'    => 'AbaLookup\Controller\ParentProfileController',
			'AbaLookup\Controller\Schedule'         => 'AbaLookup\Controller\ScheduleController',
			'AbaLookup\Controller\TherapistProfile' => 'AbaLookup\Controller\TherapistProfileController',
			'AbaLookup\Controller\User'             => 'AbaLookup\Controller\UserController',
		),
	),
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map' => array(
			'layout/layout'            => __DIR__ . '/../view/layout/layout.phtml',
			'layout/layout_home'       => __DIR__ . '/../view/layout/layout_home.phtml',
			'layout/layout_about'      => __DIR__ . '/../view/layout/layout_about.phtml',
			'layout/layout_logged_out' => __DIR__ . '/../view/layout/layout_logged_out.phtml',
			'abalookup/index/index'    => __DIR__ . '/../view/abalaunch/index/edit.phtml',
			'error/404'                => __DIR__ . '/../view/error/404.phtml',
			'error/index'              => __DIR__ . '/../view/error/edit.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
);
