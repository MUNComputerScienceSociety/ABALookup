<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
 
 

defined('APP_ROOT') || define('APP_ROOT', realpath(dirname(__FILE__) . '/../'));

$a = "bar";

set_include_path(implode(PATH_SEPARATOR, array(
	APP_ROOT . '/vendor/zf2/library/',
	APP_ROOT . '/module/',
	get_include_path()
)));


// Setup autoloading
require '../init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require '../config/application.config.php')->run();
