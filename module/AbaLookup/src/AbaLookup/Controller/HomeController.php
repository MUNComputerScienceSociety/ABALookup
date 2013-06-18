<?php

namespace AbaLookup\Controller;

use
	AbaLookup\AbaLookupController,
	Zend\View\Model\ViewModel
;

class HomeController extends AbaLookupController
{
	/**
	 * Display the home page
	 */
	public function indexAction()
	{
		$this->layout('layout/home');
		$layout = $this->layout();
		$user = $this->currentUser();
		$this->prepareLayout($layout, $user);
		return ['user' => $user];
	}

	/**
	 * Display the about page
	 */
	public function aboutAction()
	{
		$this->layout('layout/home');
		$layout = $this->layout();
		$user = $this->currentUser();
		$this->prepareLayout($layout, $user);
		return ['user' => $user];
	}

	/**
	 * Display the privacy policy
	 */
	public function privacyAction()
	{
		$this->layout('layout/home');
		$layout = $this->layout();
		$user = $this->currentUser();
		$this->prepareLayout($layout, $user);
		return ['user' => $user];
	}

	/**
	 * Display the terms of service
	 */
	public function termsAction()
	{
		$this->layout('layout/home');
		$layout = $this->layout();
		$user = $this->currentUser();
		$this->prepareLayout($layout, $user);
		return ['user' => $user];
	}
}
