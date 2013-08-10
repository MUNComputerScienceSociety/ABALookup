<?php

namespace AbaLookup;

/**
 * Controller for home actions
 */
class HomeController extends AbaLookupController
{
	/**
	 * Displays the home page
	 *
	 * @return array
	 */
	public function indexAction()
	{
		// Set the home layout
		$this->layout('layout/home');
		$layout = $this->layout();
		$user = $this->currentSessionUser();
		// Prepare the layout
		$this->prepareLayout($layout, $user);
		return [
			'user' => $user,
		];
	}

	/**
	 * Displays the privacy policy
	 *
	 * @return array
	 */
	public function privacyAction()
	{
		return $this->indexAction();
	}

	/**
	 * Displays the about page
	 *
	 * @return array
	 */
	public function aboutAction()
	{
		return $this->indexAction();
	}

	/**
	 * Displays the terms of service
	 *
	 * @return array
	 */
	public function termsAction()
	{
		return $this->indexAction();
	}

	/**
	 * Displays information about the sponsors
	 *
	 * @return array
	 */
	public function sponsorsAction()
	{
		return $this->indexAction();
	}

	/**
	 * Displays site colophon
	 *
	 * @return array
	 */
	public function colophonAction()
	{
		return $this->indexAction();
	}
}
