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
		$em = $event->getTarget()->getEventManager();
		$em->attach(MvcEvent::EVENT_FINISH, [$this, 'minify']);
		$em->attach(MvcEvent::EVENT_FINISH, [$this, 'csp']);
	}

	public function minify(MvcEvent $e)
	{
		$response = $e->getResponse();
		$response->setContent(preg_replace('#>\s+<#s', '><', $response->getBody()));
	}

	public function csp(MvcEvent $e)
	{
		$response = $e->getResponse();
		$response->getHeaders()
		         ->addHeaderLine('Content-Security-Policy', 'default-src \'self\'')
		;
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
