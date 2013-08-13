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
	 * @var Doctrine\ORM\EntityManager
	 *
	 * The central access point to ORM functionality
	 */
	private $entityManager;

	/**
	 * Returns the EntityManager
	 *
	 * @return EntityManager
	 */
	protected function getEntityManager()
	{
		if (!isset($this->entityManager)) {
			$this->entityManager = $this->getServiceLocator()
			                            ->get('doctrine.entitymanager.orm_default');
		}
		return $this->entityManager;
	}

	/**
	 * Returns the user associated with the given email address
	 *
	 * @param string $email The email address for the user.
	 * @return User
	 */
	protected function getUserByEmail($email)
	{
		$criteria = [
			'email' => $email,
		];
		return $this->getEntityManager()
		            ->getRepository('AbaLookup\Entity\User')
		            ->findOneBy($criteria);
	}

	/**
	 * Returns the user associated with the given ID
	 *
	 * @param int $id The ID for the user.
	 * @return User
	 */
	protected function getUserById($id)
	{
		$criteria = [
			'id' => $id,
		];
		return $this->getEntityManager()
		            ->getRepository('AbaLookup\Entity\User')
		            ->findOneBy($criteria);
	}

	/**
	 * Returns the given user's schedule
	 *
	 * @param User $user The user to whom the schedule belongs.
	 * @return Schedule
	 */
	protected function getUserSchedule(User $user)
	{
		$criteria = [
			'user' => $user->getId(),
		];
		$schedule = $this->getEntityManager()
		                 ->getRepository('AbaLookup\Entity\Schedule')
		                 ->findOneBy($criteria);
		if (!$schedule) {
			$schedule = new Schedule($user);
			$this->save($schedule);
		}
		return $schedule;
	}

	/**
	 * Tells the EntityManager to make an instance managed and persistent
	 * and flushes all changes to the entity
	 *
	 * This effectively synchronizes the in-memory state of the entity with the database.
	 *
	 * @param object $entity The entity to save.
	 */
	protected function save($entity)
	{
		$entityManager = $this->getEntityManager();
		$entityManager->persist($entity);
		$entityManager->flush();
	}

	/**
	 * Returns a redirect to the login page
	 *
	 * @return ViewModel
	 */
	protected function redirectToLoginPage() {
		$params = [
			'action' => 'login',
		];
		return $this->redirect()->toRoute('auth', $params);
	}

	/**
	 * Sets the session for the given user
	 *
	 * @param User $user The user in session.
	 */
	protected function setUserSession(User $user, $remember = FALSE)
	{
		$session = new Container(self::SESSION_USER_NAMESPACE);
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
	 * Returns the current user in session
	 *
	 * @return User|NULL
	 */
	protected function currentSessionUser()
	{
		if (!$this->isUserInSession()) {
			return NULL;
		}
		$session = new Container(self::SESSION_USER_NAMESPACE);
		$criteria = [
			'id' => $session->offsetGet(self::SESSION_USER_ID_KEY),
		];
		return $this->getEntityManager()
		            ->getRepository('AbaLookup\Entity\User')
		            ->findOneBy($criteria);
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
