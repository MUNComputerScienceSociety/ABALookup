<?php

namespace Lookup;

/**
 * Module class
 */
class Module
{
	/**
	 * Returns the module configuration
	 */
	public function getConfig()
	{
		return include realpath(sprintf('%s/config/module.config.php', __DIR__));
	}

	public function getAutoloaderConfig()
	{
		return [
			'Zend\Loader\StandardAutoloader' => [
				'namespaces' => [
					__NAMESPACE__ => realpath(sprintf('%s/src/%s', __DIR__, __NAMESPACE__))
				],
			],
		];
	}
}
