<?php

namespace AbaLookupTest;

use
	RuntimeException,
	Zend\Loader\AutoloaderFactory,
	Zend\Mvc\Service\ServiceManagerConfig,
	Zend\ServiceManager\ServiceManager
;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * PHPUnit bootstrap file
 */
class Bootstrap
{
	/**
	 * @var Zend\ServiceManager\ServiceManager
	 */
	protected static $serviceManager;

	/**
	 * Return the full path to the given folder
	 *
	 * Return the full path to the given
	 * folder provided it lives as a parent
	 * to the current folder.
	 */
	protected static function findParentPath($path)
	{
		$dir = __DIR__;
		$previousDir = '.';
		while (!is_dir($dir . '/' . $path)) {
			$dir = dirname($dir);
			if ($previousDir === $dir) {
				return false;
			}
			$previousDir = $dir;
		}
		return $dir . '/' . $path;
	}

	/**
	 * Return the the ServiceManager
	 */
	public static function getServiceManager()
	{
		return static::$serviceManager;
	}

	/**
	 * Initialise the bootstrap class
	 */
	public static function init()
	{
		// The path to the module directory
		$zf2ModulePaths = [dirname(dirname(__DIR__))];
		// Find the vendor directory
		if (($path = static::findParentPath('vendor'))) {
			$zf2ModulePaths[] = $path;
		}
		// Check for another module directory
		if (($path = static::findParentPath('module')) !== $zf2ModulePaths[0]) {
			$zf2ModulePaths[] = $path;
		}

		static::initAutoloader();

		// Use ModuleManager to load this module and its dependencies
		$config = [
			'module_listener_options' => [
				'module_paths' => $zf2ModulePaths,
			],
			'modules' => [
				'Lookup',
			],
		];
		$serviceManager = new ServiceManager(new ServiceManagerConfig());
		$serviceManager->setService('ApplicationConfig', $config);
		$serviceManager->get('ModuleManager')->loadModules();
		static::$serviceManager = $serviceManager;
	}

	/**
	 * Load the autoloader file
	 */
	protected static function initAutoloader()
	{
		// Autoload file
		$autoloadFile = realpath(sprintf(
			'%s/autoload.php',
			static::findParentPath('vendor')
		));
		// Composer autoloading
		if (file_exists($autoloadFile)) {
			$loader = include $autoloadFile;
		}
		if (!class_exists('Zend\Loader\AutoloaderFactory')) {
			throw new RuntimeException('Unable to load ZF2. Run "composer install".');
		}
		AutoloaderFactory::factory([
			'Zend\Loader\StandardAutoloader' => [
				'autoregister_zf' => TRUE,
				'namespaces'      => [
					__NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
				],
			],
		]);
	}
}

Bootstrap::init();
