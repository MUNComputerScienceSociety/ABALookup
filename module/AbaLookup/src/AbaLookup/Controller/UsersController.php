<?php

namespace AbaLookup\Controller;

use
	AbaLookup\AbaLookupController,
	AbaLookup\Entity\Schedule,
	AbaLookup\Entity\User,
	AbaLookup\Form\LoginForm,
	AbaLookup\Form\ProfileEditForm,
	AbaLookup\Form\RegisterForm,
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
	 * and proceeds to log the user in (and show their profile).
	 */
	public function registerAction()
	{
		$form = new RegisterForm();
		$request = $this->request;
		if (!$request->isPost()) {
			// the user has not submitted
			// the registration form
			return ['form' => $form];
		}
		// the user has attempted to register
		$form->setData($request->getPost());
		if (!$form->isValid()) {
			return [
				'form' => $form,
				'error' => $form->getMessage(),
			];
		}
		// form is valid
		$user = $form->getUser();
		if ($this->getUserByEmail($user->getEmail())) {
			// the given email address is already in use
			return [
				'form' => $form,
				'error' => 'The email address is already in use.'
			];
		}
		$user->setVerified(TRUE);
		// enter the user into the database
		$entityManager = $this->getEntityManager();
		$entityManager->persist($user);
		$entityManager->flush();
		// log the user in
		$session = new Container();
		$session->offsetSet(self::SESSION_KEY, $user->getId());
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
		$form = new LoginForm();
		$request = $this->request;
		// the user has not attempted to login
		if (!$request->isPost()) {
			// show the login form
			return ['form' => $form];
		}
		// the user has attempted to login
		// retrieve the form values
		$form->setData($request->getPost());
		if (!$form->isValid()) {
			return [
				'form' => $form,
				'error' => $form->getMessage(),
			];
		}
		// retrieve the validated values
		$emailAddress = $form->getEmailAddress();
		$password = $form->getPassword();
		// get user by email
		$user = $this->getUserByEmail($emailAddress);
		if (!$user || !$user->verifyPassword($password)) {
			return [
				'form' => $form,
				'error' => 'The entered credentials are not valid.'
			];
		}
		// login the user
		$session = new Container();
		$session->offsetSet(self::SESSION_KEY, $user->getId());
		return $this->redirect()->toRoute('users', [
			'id' => $user->getId(),
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
		if ($session->offsetExists(self::SESSION_KEY)) {
			$session->offsetUnset(self::SESSION_KEY);
		}
		return $this->redirect()->toRoute('home');
	}

	/**
	 * Display the user's profile
	 *
	 * Editing capabilites for the user profile
	 * have a this same route with a parameter
	 * of 'mode' => 'edit' which should show a
	 * form for editing the user's profile information.
	 */
	public function profileAction()
	{
		if (($user = $this->currentUser()) == NULL) {
			// no user is in session
			return $this->redirectToLoginPage();
		}
		// prepare the view layout
		$layout = $this->layout();
		$this->prepareLayout($layout, $user);
		// if mode != edit, then user is viewing
		if ($this->params('mode', NULL) != 'edit') {
			// show the user's profile
			return ['user' => $user];
		}
		// the user is editing their profile
		$form = new ProfileEditForm($user);
		$edit = new ViewModel([
			'user' => $user,
			'form' => $form,
		]);
		$edit->setTemplate('users/profile-edit');
		$request = $this->request;
		if (!$request->isPost()) {
			// show the profile edit form
			return $edit;
		}
		$form->setData($request->getPost());
		if (!$form->isValid() || !$form->updateUser($user)) {
			$edit->setVariable('error', $form->getMessage());
			return $edit;
		}
		// update the user's information
		$entityManager = $this->getEntityManager();
		$entityManager->persist($user);
		$entityManager->flush();
		// show the user's profile again
		return $this->redirect()->toRoute('users', [
			'id'     => $user->getId(),
			'action' => 'profile',
		]);
	}

	/**
	 * Display the user's schedule
	 *
	 * Edits to the schedule arrive via POST with
	 * a parameter of 'mode' => 'add'.
	 */
	public function scheduleAction()
	{
		// no user in session
		if (($user = $this->currentUser()) == NULL) {
			return $this->redirectToLoginPage();
		}
		// prepare the view layout
		$layout = $this->layout();
		$this->prepareLayout($layout, $user);
		// the user's schedule
		$schedule = $this->getUserSchedule($user);
		// check for schedule availabilities
		$request = $this->request;
		if ($request->isPost() && $this->params('mode', NULL) == 'add') {
			// TODO add the availability to the user's schedule
		}
		// show ther user's schedule
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
		// prepare the view layout
		$layout = $this->layout();
		$this->prepareLayout($layout, $user);
		// show the user's matches
		return ['user' => $user];
	}
}
