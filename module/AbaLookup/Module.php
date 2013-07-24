<?php

namespace AbaLookup;

class Module
{
	/**
	 * Return the module configuration
	 */
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig()
	{
		return ['Zend\Loader\StandardAutoloader' => [
			'namespaces' => [__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__],
		]];
	}

	/**
	 * Return the list of view helpers
	 */
	public function getViewHelperConfig()
	{
		return [
			'invokables' => [
				'schedule' => 'AbaLookup\View\Helper\Schedule',
			],
		];
	}
}
