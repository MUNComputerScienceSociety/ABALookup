<?php

namespace AbaLookup;

use
	Zend\EventManager\EventInterface,
	Zend\Mvc\MvcEvent
;

/**
 * Module class
 */
class Module
{
	public function onBootstrap(EventInterface $event)
	{
		$event->getTarget()
		      ->getEventManager()
		      ->attach('finish', [$this, 'minify']);
	}

	public function minify(MvcEvent $e)
	{
		$response = $e->getResponse();
		$response->setContent(preg_replace('#>\s+<#s', '><', $response->getBody()));
	}

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

	/**
	 * Returns the view helpers mapping
	 */
	public function getViewHelperConfig()
	{
		return [
			'invokables' => [
				'anchor'         => 'AbaLookup\View\Helper\AnchorLink',
				'scheduleHelper' => 'AbaLookup\View\Helper\ScheduleHelper',
				'script'         => 'AbaLookup\View\Helper\Script',
				'stylesheet'     => 'AbaLookup\View\Helper\Stylesheet',
			],
		];
	}
}
