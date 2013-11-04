<?php

namespace AbaLookup;

use AbaLookup\Session\Session;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class HomeController extends AbaLookupController
{
	public function setEventManager(EventManagerInterface $events)
	{
		parent::setEventManager($events);
		$events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'beforeAction'], 100);
	}

	public function beforeAction()
	{
		// Set the home layout
		$this->layout('layout/home');
		$uid = Session::getUserId();
		try {
			$user = $this->getApi('UserAccount')
			             ->get($uid);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			// TODO - Handle this
			$user = NULL;
		}
		// Prepare the layout
		$this->prepareLayout($user);
		return [
			'user' => $user,
		];
	}
}
