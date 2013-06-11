<?php

namespace AbaLookup\Controller;

use
	AbaLookup\AbaLookupController,
	AbaLookup\Entity\Schedule,
	AbaLookup\Entity\User,
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
	 * and proceeds to log the user in.
	 */
	public function registerAction()
	{
		$form = new RegisterForm();
		$request = $this->request;
		// the user has not attempted to register
		if (!$request->isPost()) {
			// show the registration form
			return ['form' => $form];
		}
		// the user has attempted to register
		$form->setData($request->getPost());
		if (!$form->isValid()) {
			$messages = $form->getMessages();
			return [
				'form' => $form,
				'errors' => $messages,
			];
		}
		// form is valid
		$validData = $form->getData();
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
		$request = $this->request;
		// the user has not attempted to login
		if (!$request->isPost()) {
			// show the login form
			return [];
		}
		// the user has attempted to login
		// retrieve the form values
		$email    = $request->getPost('email-address');
		$password = $request->getPost('password');
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
	 *
	 * Editing capabilites for the user profile
	 * have a this same route with a parameter
	 * of 'mode' => 'edit' which should show a
	 * form for editing the user's profile information.
	 */
	public function profileAction()
	{
		// no user is in session
		if (($user = $this->currentUser()) == NULL) {
			return $this->redirectToLoginPage();
		}
		// prepare the view layout
		$layout = $this->layout();
		$this->prepareLayout($layout, $user);
		// check for user editing profile
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
