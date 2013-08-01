<?php

namespace AbaLookup;

class Module
{
	/**
	 * Return the module configuration
	 */
	public function getConfig()
	{
		return include realpath(__DIR__ . '/config/module.config.php');
	}

	public function getAutoloaderConfig()
	{
		return [
			'Zend\Loader\StandardAutoloader' => [
				'namespaces' => [
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				],
			],
		];
	}

	/**
	 * Return the view helpers mapping
	 */
	public function getViewHelperConfig()
	{
		return [
			'invokables' => [
				'scheduleHelper' => 'AbaLookup\View\Helper\ScheduleHelper',
				'script' => 'AbaLookup\View\Helper\Script',
			],
		];
	}
}
