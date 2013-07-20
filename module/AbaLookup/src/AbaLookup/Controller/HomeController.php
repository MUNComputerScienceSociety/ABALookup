<?php

namespace AbaLookup\Controller;

use AbaLookup\AbaLookupController;

class HomeController extends AbaLookupController
{
	/**
	 * Display the home page
	 */
	public function indexAction()
	{
		$this->layout('layout/home');
		$layout = $this->layout();
		$user = $this->currentSessionUser();
		$this->prepareLayout($layout, $user);
		return ['user' => $user];
	}

	/**
	 * Display the about page
	 */
	public function aboutAction()
	{
		return $this->indexAction();
	}

	/**
	 * Display the privacy policy
	 */
	public function privacyAction()
	{
		return $this->indexAction();
	}

	/**
	 * Display the terms of service
	 */
	public function termsAction()
	{
		return $this->indexAction();
	}
}
