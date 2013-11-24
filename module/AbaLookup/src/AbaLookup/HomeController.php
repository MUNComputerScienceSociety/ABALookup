<?php

namespace AbaLookup;

use AbaLookup\Session\Session;

class HomeController extends AbaLookupController
{
	/**
	 * The user in session
	 *
	 * @var Lookup\Entity\User
	 */
	protected $user;

	/**
	 * Contains logic common to all actions and is run on
	 * each dispatch
	 *
	 * @return void
	 */
	public function action()
	{
		// Set the home layout
		$this->layout('layout/home');
		$uid = Session::getUserId();
		try {
			$this->user = $this->getService('Lookup\Api\UserAccount')
			                   ->get($uid);
		} catch (\Lookup\Api\Exception\InvalidDataException $e) {
			// TODO - Handle this
			$this->user = NULL;
		}
		// Prepare the layout
		$this->prepareLayout($this->user);
	}

	public function indexAction()
	{
		return [
			'user' => $this->user,
		];
	}

	public function privacyAction()
	{
		return [
			'user' => $this->user,
		];
	}

	public function aboutAction()
	{
		return [
			'user' => $this->user,
		];
	}

	public function termsAction()
	{
		return [
			'user' => $this->user,
		];
	}

	public function sponsorsAction()
	{
		return [
			'user' => $this->user,
		];
	}

	public function colophonAction()
	{
		return [
			'user' => $this->user,
		];
	}
}
