<?php

namespace AbaLookup;

use
	AbaLookup\Entity\Schedule,
	AbaLookup\Entity\User,
	Zend\Mvc\Controller\AbstractActionController,
	Zend\Session\Container,
	Zend\View\Model\ViewModel
;

abstract class AbaLookupController extends AbstractActionController
{
	/**
	 * The key used to store the user in session
	 */
	const SESSION_USER_NAMESPACE = 'user';
	const SESSION_USER_ID_KEY    = 'id';

	/**
	 * 3 months in seconds
	 */
	const SECONDS_3_MONTHS = 7884000;

	/**
	 * Returns a redirect to the login page
	 *
	 * @return ViewModel
	 */
	protected function redirectToLoginPage() {
		return $this->redirect()->toRoute('auth/login');
	}

	/**
	 * Sets the session for the given user
	 *
	 * @param User $user The user in session.
	 * @param boolean $remember Whether to set an explicit TTL for the user session.
	 */
	protected function setUserSession(User $user, $remember = FALSE)
	{
		$session = new Container(self::SESSION_USER_NAMESPACE);
		$session->getManager()
		        ->getConfig()
		        ->setCookieHttpOnly(TRUE); // As per issue #87
		if (isset($remember) && $remember) {
			$session->getManager()
			        ->rememberMe(self::SECONDS_3_MONTHS);
		}
		$session->offsetSet(self::SESSION_USER_ID_KEY, $user->getId());
	}

	/**
	 * Unsets the session
	 */
	protected function unsetUserSession()
	{
		$session = new Container(self::SESSION_USER_NAMESPACE);
		$session->offsetUnset(self::SESSION_USER_ID_KEY);
	}

	/**
	 * Returns whether a user is currently in session
	 *
	 * @return bool
	 */
	protected function isUserInSession()
	{
		$session = new Container(self::SESSION_USER_NAMESPACE);
		return $session->offsetExists(self::SESSION_USER_ID_KEY);
	}

	/**
	 * Returns the id of the current user session
	 *
	 * @return Lookup\Entity\User|NULL
	 */
	protected function currentSessionUser()
	{
		if (!$this->isUserInSession()) {
			return NULL;
		}
		$session = new Container(self::SESSION_USER_NAMESPACE);
		return $this->getApi('UserAccount')->get($session->offsetGet(self::SESSION_USER_ID_KEY));
	}

	/**
	 * Prepares the given layout to be displayed for the given user
	 *
	 * Nests the footer widget into the layout, and attaches the current
	 * to the layout as a variable.
	 *
	 * @param ViewModel $layout The layout for the view.
	 * @param User $user The user currently in session.
	 */
	protected function prepareLayout($layout, User $user = NULL)
	{
		// Add the footer
		$footer = new ViewModel();
		$footer->setTemplate('widget/footer');
		$layout->addChild($footer, 'footer');
		// Add the user's URL
		if (!isset($user)) {
			return;
		}
		$layout->setVariable('user', $user);
	}
}
