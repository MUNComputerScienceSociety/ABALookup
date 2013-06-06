<?php

namespace AbaLookup\Controller;

use
	AbaLookup\AbaLookupController,
	AbaLookup\Entity\Schedule,
	AbaLookup\Entity\User,
	Zend\Session\Container,
	Zend\View\Model\ViewModel
;

/**
 * Handles all actions involving users
 */
class UsersController extends AbaLookupController
{
	/**
	 * Return the user associated with the given email address
	 */
	private function getUserByEmail($email)
	{
		return $this->getEntityManager()->getRepository('AbaLookup\Entity\User')->findOneBy(['email' => $email]);
	}

	/**
	 * Return the user associated with the given ID
	 */
	private function getUserById($id)
	{
		return $this->getEntityManager()->getRepository('AbaLookup\Entity\User')->findOneBy(['id' => $id]);
	}

	/**
	 * Return a redirect to the login page
	 */
	private function redirectToLoginPage() {
		return $this->redirect()->toRoute('auth', ['action' => 'login']);
	}

	/**
	 * Return the given user's schedule
	 */
	private function getUserSchedule($user)
	{
		$entityManager = $this->getEntityManager();
		$schedule = $entityManager->getRepository('AbaLookup\Entity\Schedule')
		                          ->findOneBy(['user' => $user->getId()]);
		if (!$schedule) {
			$schedule = new Schedule($user);
			$entityManager->persist($schedule);
			$entityManager->flush();
		}
		return $schedule;
	}

	/**
	 * Register the user
	 *
	 * Validates the user's information, saves them into the database
	 * and proceeds to log the user in.
	 */
	public function registerAction()
	{
		$this->layout('layout/logged-out');
		$request = $this->request;
		// the user has not attempted to register
		if (!$request->isPost()) {
			// show the registration form
			return [];
		}
		// the user has attempted to register
		$userType        = $request->getPost('user-type');
		$displayName     = $request->getPost('display-name');
		$email           = $request->getPost('email-address');
		$password        = $request->getPost('password');
		$confirmPassword = $request->getPost('confirm-password');
		// validate the user input
		if (empty($userType)
		    || empty($displayName)
		    || empty($email)
		    || empty($password)
		    || empty($confirmPassword)
		) {
			// the user did not complete the form
			return [
				"error"       => "All fields are required.",
				"userType"    => $userType,
				"displayName" => $displayName,
				"email"       => $email,
			];
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// the user entered an invalid email address
			return [
				"error"       => "A valid email address is required.",
				"userType"    => $userType,
				"displayName" => $displayName,
				"email"       => $email,
			];
		} elseif ($confirmPassword != $password) {
			// the user did not confirm their password choice
			return [
				"error"       => "Your passwords do not match.",
				"userType"    => $userType,
				"displayName" => $displayName,
				"email"       => $email,
			];
		} elseif (strlen($password) < User::MINIMUM_PASSWORD_LENGTH) {
			// the entered password length is poor
			return [
				"error"       => "Your password must be at least 6 characters in length.",
				"userType"    => $userType,
				"displayName" => $displayName,
				"email"       => $email,
			];
		} elseif ($this->getUserByEmail($email)) {
			// the given email address is already in use
			return [
				"error"       => "This email address is already in use.",
				"userType"    => $userType,
				"displayName" => $displayName,
				"email"       => $email,
			];
		}
		// the information entered is okay
		// create the user
		$user = new User($displayName, $email, $password, ($userType === "therapist"), NULL, FALSE, FALSE);
		$user->setVerified(TRUE);
		// enter the user into the database
		$entityManager = $this->getEntityManager();
		$entityManager->persist($user);
		$entityManager->flush();
		// log the user in
		$session = new Container();
		$session->offsetSet("user", $user->getId());
		return $this->redirect()->toRoute('users', [
			'id'     => $user->getId(),
			'action' => 'profile',
		]);
	}

	/**
	 * Login the user
	 *
	 * Verify the user credentials and if valid login user in.
	 */
	public function loginAction()
	{
		$this->layout('layout/logged-out');
		// the user has not attempted to login
		if (!isset($_POST['login'])) {
			// show the login form
			return [];
		}
		// the user has attempted to login
		// retrieve the form values
		$email    = $_POST['email-address'];
		$password = $_POST['password'];
		$user = $this->getUserByEmail($email);
		if (!$user || !$user->verifyPassword($password)) {
			return ['error' => 'The entered credentials are not valid.'];
		}
		// login the user
		$session = new Container();
		$session->offsetSet('user', $user->getId());
		return $this->redirect()->toRoute('users', [
			'id'     => $user->getId(),
			'action' => 'profile',
		]);
	}

	/**
	 * Logout the user
	 *
	 * If a user is logged in, log them out.
	 * Invalidates the session.
	 * Reroutes the user to the home page.
	 */
	public function logoutAction()
	{
		$session = new Container();
		if ($session->offsetExists('user')) {
			$session->offsetUnset('user');
		}
		return $this->redirect()->toRoute('home');
	}

	/**
	 * Display the user's profile
	 */
	public function profileAction()
	{
		// no user is in session
		if (($user = $this->currentUser()) == NULL) {
			return $this->redirectToLoginPage();
		}
		$layout = $this->layout();
		$this->prepareLayout($layout, $user);
		if ($this->params('mode', NULL) != 'edit') {
			// show the user's profile
			return ['user' => $user];
		}
		// check for profile edits
		$request = $this->request;
		if (!$request->isPost()) {
			// show the profile edit form
			$edit = new ViewModel(['user' => $user]);
			$edit->setTemplate('users/profile-edit');
			return $edit;
		}
		// update the user's information
		$displayName = $request->getPost('display-name', NULL);
		$email = $request->getPost('email-address', $user->getEmail());
		$sex = $request->getPost('sex', NULL);
		$user->setDisplayName($displayName);
		$user->setEmail($email);
		$user->setSex($sex == "Undisclosed" ? NULL : $sex);
		// change the user's password
		$oldPassword = $request->getPost('old-password', NULL);
		$newPassword = $request->getPost('new-password', NULL);
		$newConfirmPassword = $request->getPost('new-confirm-password', NULL);
		if ($oldPassword
		    && $user->verifyPassword($oldPassword)
		    && $newPassword
		    && (strlen($newPassword) >= User::MINIMUM_PASSWORD_LENGTH)
		    && $newConfirmPassword
		    && ($newPassword == $newConfirmPassword)) {
			// change the user's password
			$user->setPassword($newPassword);
		} else {
			// the user did not change their password
			// TODO allow the user to skip changing their password
			$edit = new ViewModel([
				'user'  => $user,
				'error' => 'Error',
			]);
			$edit->setTemplate('users/profile-edit');
			return $edit;
		}
		// persist the changes
		$entityManager = $this->getEntityManager();
		$entityManager->persist($user);
		$entityManager->flush();
		// show the user's profile
		return $this->redirect()->toRoute('users', [
			'id'     => $user->getId(),
			'action' => 'profile',
		]);
	}

	/**
	 * Display the user's schedule
	 */
	public function scheduleAction()
	{
		// no user in session
		if (($user = $this->currentUser()) == NULL) {
			return $this->redirectToLoginPage();
		}
		$layout = $this->layout();
		$this->prepareLayout($layout, $user);
		// the user's schedule
		$schedule = $this->getUserSchedule($user);
		return [
			'user'      => $user,
			'schedule'  => $schedule,
		];
	}

	/**
	 * Display the user's matches
	 */
	public function matchesAction()
	{
		if (($user = $this->currentUser()) == NULL) {
			return $this->redirectToLoginPage();
		}
		$layout = $this->layout();
		$this->prepareLayout($layout, $user);
		return ['user' => $user];
	}
}
