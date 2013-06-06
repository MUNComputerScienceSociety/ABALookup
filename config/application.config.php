<?php

return [
	// module namespaces used in the application
	'modules' => [
		'AbaLookup',
		'DoctrineModule',
		'DoctrineORMModule',
	],
	// options for the listeners attached to the ModuleManager
	'module_listener_options' => [
		// the paths in which modules reside
		'module_paths' => [
			'./module',
			'./vendor',
		],
		// paths from which to glob configuration files after modules are loaded
		// these effectively overide configuration provided by modules themselves
		'config_glob_paths' => [
			'config/autoload/{,*.}{global,local}.php',
		],
	],
];
