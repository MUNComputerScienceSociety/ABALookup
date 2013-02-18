<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

echo get_include_path();

set_include_path(implode(PATH_SEPARATOR, array(
	'vendor/zf2/library/',
	'module/',
	get_include_path()
)));


// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
