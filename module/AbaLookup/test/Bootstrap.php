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
	 *
	 */
	public static function init()
	{
		// the path to the module directory
		$zf2ModulePaths = [dirname(dirname(__DIR__))];
		// find the vendor directory
		if (($path = static::findParentPath('vendor'))) {
			$zf2ModulePaths[] = $path;
		}
		// check for another module directory
		if (($path = static::findParentPath('module')) !== $zf2ModulePaths[0]) {
			$zf2ModulePaths[] = $path;
		}

		static::initAutoloader();

		// use ModuleManager to load this module and it's dependencies
		$config = [
			'module_listener_options' => ['module_paths' => $zf2ModulePaths],
			'modules' => ['AbaLookup'],
		];

		$serviceManager = new ServiceManager(new ServiceManagerConfig());
		$serviceManager->setService('ApplicationConfig', $config);
		$serviceManager->get('ModuleManager')->loadModules();
		static::$serviceManager = $serviceManager;
	}

	/**
	 *
	 */
	protected static function initAutoloader()
	{
		// the path to the vendor directory
		$vendorPath = static::findParentPath('vendor');

		// Composer autoloading
		if (file_exists($vendorPath . '/autoload.php')) {
			$loader = include $vendorPath . '/autoload.php';
		}
		if (!class_exists('Zend\Loader\AutoloaderFactory')) {
			throw new RuntimeException('Unable to load ZF2. Run `composer install`.');
		}

		AutoloaderFactory::factory([
			'Zend\Loader\StandardAutoloader' => [
				'autoregister_zf' => true,
				'namespaces' => [__NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__],
			],
		]);
	}
}

Bootstrap::init();
