<?php

namespace AbaLookup\Controller;

use
	AbaLookup\AbaLookupController,
	AbaLookup\Form\LoginForm,
	AbaLookup\Form\ProfileEditForm,
	AbaLookup\Form\RegisterForm,
	InvalidArgumentException,
	Zend\View\Model\ViewModel
;

/**
 * Handles all actions involving users
 */
class UsersController extends AbaLookupController
{
	/**
	 * Register the user
	 *
	 * Validates the user's information, saves them into the database
	 * and proceeds to log the user in (and show their profile).
	 */
	public function registerAction()
	{
		$user = $this->currentSessionUser();
		if (isset($user)) {
			return $this->redirect()->toRoute('users', [
				'id' => $user->getId(),
				'action' => 'profile'
			]);
		}

		// prepare the view layout
		$this->prepareLayout($this->layout());

		// get the registration form
		$form = new RegisterForm();

		if (!$this->request->isPost()) {
			// the user has not submitted
			// the registration form
			return ['form' => $form];
		}

		// the user has attempted to register
		$form->setData($this->request->getPost());
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
				'error' => 'The entered email address is already in use.'
			];
		}
		$user->setVerified(TRUE);

		// enter the user into the database
		$this->save($user);

		$this->setUserSession($user);
		return $this->redirect()->toRoute('users', [
			'id'     => $user->getId(),
			'action' => 'profile'
		]);
	}

	/**
	 * Login the user
	 *
	 * Verify the user credentials and if valid login user in.
	 */
	public function loginAction()
	{
		$user = $this->currentSessionUser();
		if (isset($user)) {
			return $this->redirect()->toRoute('users', [
				'id' => $user->getId(),
				'action' => 'profile'
			]);
		}

		// prepare the view layout
		$this->prepareLayout($this->layout());

		// get the login form
		$form = new LoginForm();

		if (!$this->request->isPost()) {
			// the user has not attempted to login
			// show the login form
			return ['form' => $form];
		}

		// the user has attempted to login
		// retrieve the form values
		$form->setData($this->request->getPost());
		if (!$form->isValid()) {
			return [
				'form' => $form,
				'error' => $form->getMessage(),
			];
		}

		// retrieve the validated values
		$emailAddress = $form->getEmailAddress();
		$password = $form->getPassword();
		$user = $this->getUserByEmail($emailAddress);
		if (!$user || !$user->verifyPassword($password)) {
			return [
				'form' => $form,
				'error' => 'The entered credentials are not valid.'
			];
		}

		$this->setUserSession($user, $form->rememberMe());
		return $this->redirect()->toRoute('users', [
			'id' => $user->getId(),
			'action' => 'profile'
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
		if ($this->isUserInSession()) {
			$this->unsetUserSession();
		}
		return $this->redirect()->toRoute('home');
	}

	/**
	 * Display the user's profile
	 *
	 * Edits to the user's profile arrive via POST with
	 * a parameter of 'mode' => 'edit'.
	 */
	public function profileAction()
	{
		$user = $this->currentSessionUser();
		if (!isset($user)) {
			return $this->redirectToLoginPage();
		}

		// prepare the view layout
		$this->prepareLayout($this->layout(), $user);

		// if mode is not edit, then user is viewing
		if ($this->params('mode') !== 'edit') {
			// show the user's profile
			return ['user' => $user];
		}

		// the user is editing their profile
		$form = new ProfileEditForm($user);
		$edit = new ViewModel(['user' => $user, 'form' => $form]);
		$edit->setTemplate('users/profile-edit');
		if (!$this->request->isPost()) {
			// if not POST show the profile edit form
			return $edit;
		}

		$form->setData($this->request->getPost());
		if (!$form->isValid() || !$form->updateUser($user)) {
			$edit->setVariable('error', $form->getMessage());
			return $edit;
		}

		// update the user's information
		$this->save($user);

		// show the user's profile again
		return $this->redirect()->toRoute('users', [
			'id' => $user->getId(),
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
		$user = $this->currentSessionUser();
		if (!isset($user)) {
			return $this->redirectToLoginPage();
		}

		// prepare the view layout
		$this->prepareLayout($this->layout(), $user);
		// the user's schedule
		$schedule = $this->getUserSchedule($user);

		// check for schedule availabilities
		if ($this->request->isPost()) {
			if ($this->params('mode') === 'edit') {
				// add the availability to the user's schedule
				$params = array_values($this->request->getPost()->toArray());
				list($day, $startTime, $endTime, $addRemove) = $params;
				try {
					$schedule->setAvailability(
						(int) $day,
						(int) $startTime,
						(int) $endTime,
						($addRemove == 'add')
					);
				} catch (InvalidArgumentException $e) {
					return [
						'error' => $e->getMessage(),
						'user' => $user,
						'schedule' => $schedule
					];
				}
				$this->save($schedule);
				return $this->redirect()->toRoute('users', [
					'id' => $user->getId(),
					'action' => 'schedule',
				]);
			}
		}

		// show ther user's schedule
		return ['user' => $user, 'schedule' => $schedule];
	}

	/**
	 * Display the user's matches
	 */
	public function matchesAction()
	{
		$user = $this->currentSessionUser();
		if (!isset($user)) {
			return $this->redirectToLoginPage();
		}

		// prepare the view layout
		$this->prepareLayout($this->layout(), $user);

		// show the user's matches
		return ['user' => $user];
	}
}
