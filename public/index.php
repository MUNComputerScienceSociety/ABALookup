<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

set_include_path(implode(PATH_SEPARATOR, [
	'vendor/zf2/library/',
	'module/',
	get_include_path(),
]));

require 'autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
