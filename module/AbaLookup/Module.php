<?php

namespace AbaLookup;

use
	Zend\Mvc\ModuleRouteListener,
	Zend\Mvc\MvcEvent
;

class Module
{
	/**
	 * Include the module configuration
	 */
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	/**
	 *
	 */
	public function getAutoloaderConfig()
	{
		return ['Zend\Loader\StandardAutoloader' => [
			'namespaces' => [__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__],
		]];
	}
}
