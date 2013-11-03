<?php

namespace AbaLookup;

use
	AbaLookup\Form\LoginForm,
	AbaLookup\Form\ProfileEditForm,
	AbaLookup\Form\RegisterForm,
	Zend\View\Model\ViewModel
;

/**
 * Handles all actions involving users
 */
class UsersController extends AbaLookupController
{
	/**
	 * Registers the user or shows a registration form
	 *
	 * Shows the registration form, validates the form data, creates a user from
	 * the submitted data, and then adds the user into the database.
	 *
	 * @return array|Zend\Http\Response
	 */
	public function registerAction()
	{
		// If a user is already logged in
		$user = $this->currentSessionUser();
		if (isset($user)) {
			// Redirect the user to their profile page
			$params = [
				'id' => $user->getId(),
				'action' => 'profile',
			];
			return $this->redirect()->toRoute('users', $params);
		}
		// Prepare the view layout
		$this->prepareLayout($this->layout());
		// Get the user type from the URL
		// Create a registration form
		$type = $this->params('type');
		$form = new RegisterForm($type);
		// If the user has not submitted a POST request
		if (!$this->request->isPost()) {
			// The user has not submitted the form
			return [
				'form' => $form,
				'type' => $type,
			];
		}
		// The user has submitted via POST
		// TODO - Validate Terms of Service
		// TODO - Show previous data to user
		$data = $this->params();
		try {
			$id = $this->getApi('UserAccount')->put(
				$data->fromPost($form::ELEMENT_NAME_EMAIL_ADDRESS),
				$data->fromPost($form::ELEMENT_NAME_PASSWORD),
				$data->fromPost($form::ELEMENT_NAME_DISPLAY_NAME),
				$data->fromPost($form::ELEMENT_NAME_USER_TYPE),
				$data->fromPost($form::ELEMENT_NAME_POSTAL_CODE),
				array_intersect_key(
					$data->fromPost(),
					array_flip([
						$form::ELEMENT_NAME_ABA_COURSE,
						$form::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT,
						$form::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE,
						$form::ELEMENT_NAME_GENDER,
						$form::ELEMENT_NAME_PHONE_NUMBER,
					])
				)
			);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			return [
				'error' => $e->getMessage(),
				'form'  => $form,
				'type'  => $type,
			];
		}
		$this->setUserSession($id);
		// Redirect the user to their profile page
		$params = [
			'id'     => $id,
			'action' => 'profile',
		];
		return $this->redirect()->toRoute('users', $params);
	}

	/**
	 * Logs the user in
	 *
	 * Verify the user credentials and if valid login user in.
	 *
	 * @return array|Zend\Http\Response
	 */
	public function loginAction()
	{
		// If a user is already logged in
		$user = $this->currentSessionUser();
		if (isset($user)) {
			// Show the user their profile page
			$params = [
				'id'     => $user->getId(),
				'action' => 'profile',
			];
			return $this->redirect()->toRoute('users', $params);
		}
		// Prepare the view layout
		$this->prepareLayout($this->layout());
		// Create a login form
		$form = new LoginForm();
		// If the user has not submitted a POST request
		if (!$this->request->isPost()) {
			// Show the login form
			return [
				'form' => $form,
			];
		}
		// The user has submitted via POST
		$data = $this->params();
		try {
			$user = $this->getApi('UserAccount')->get([
				'email' => $data->fromPost($form::ELEMENT_NAME_EMAIL_ADDRESS),
				'password' => $data->fromPost($form::ELEMENT_NAME_PASSWORD),
			]);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			return [
				'error' => $e->getMessage(),
				'form'  => $form,
			];
		}
		// Create a session for the user
		$id = $user->getId();
		$this->setUserSession($id, $form->rememberMe());
		$params = [
			'id'     => $id,
			'action' => 'profile',
		];
		return $this->redirect()->toRoute('users', $params);
	}

	/**
	 * Logs the user out
	 *
	 * If a user is logged in, log them out. Invalidates the session.
	 * Reroutes the user to the home page.
	 *
	 * @return Zend\Http\Response
	 */
	public function logoutAction()
	{
		if ($this->isUserInSession()) {
			$this->unsetUserSession();
		}
		return $this->redirect()->toRoute('home');
	}

	/**
	 * Displays the user's profile
	 *
	 * Edits to the user's profile arrive via POST with
	 * a parameter of 'mode' => 'edit'.
	 *
	 * @return array|Zend\Http\Response
	 */
	public function profileAction()
	{
		// If a user is not in session
		$user = $this->currentSessionUser();
		if (!isset($user)) {
			// Redirect to the login view
			return $this->redirectToLoginPage();
		}
		// Prepare the layout
		$this->prepareLayout($this->layout(), $user);
		// If the mode is not edit
		// The user is viewing their profile
		if ($this->params('mode') !== 'edit') {
			// Show the user's profile
			return [
				'user' => $user,
			];
		}
		// The user is editing their profile info
		$form = new ProfileEditForm($user);
		$edit = new ViewModel([
			'user' => $user,
			'form' => $form,
		]);
		$edit->setTemplate('profile/edit');
		// If the user has not submitted a POST request
		if (!$this->request->isPost()) {
			// Show the edit form
			return $edit;
		}
		// The user has submitted via POST
		$data = $this->params();
		try {
			$this->getApi('UserAccount')->post($user->getId(), [
				'email' => $data->fromPost($form::ELEMENT_NAME_EMAIL_ADDRESS),
				'display_name' => $data->fromPost($form::ELEMENT_NAME_DISPLAY_NAME),
				'postal_code' => $data->fromPost($form::ELEMENT_NAME_POSTAL_CODE),
				'aba_course' => $data->fromPost($form::ELEMENT_NAME_ABA_COURSE),
				'certificate_of_conduct' => $data->fromPost($form::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE),
				'gender' => $data->fromPost($form::ELEMENT_NAME_GENDER),
				'phone_number' => $data->fromPost($form::ELEMENT_NAME_PHONE_NUMBER),
			]);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			$edit->setVariable('error', $e->getMessage());
			return $edit;
		}
		// Redirect to the profile page
		return $this->redirect()->toRoute('users', [
			'id' => $user->getId(),
			'action' => 'profile',
		]);
	}

	/**
	 * Displays the user's schedule
	 *
	 * Edits to the schedule arrive via POST with a 'mode' == 'add'.
	 *
	 * @return array|Zend\Http\Response
	 */
	public function scheduleAction()
	{
		// If a user is not in session
		$user = $this->currentSessionUser();
		if (!isset($user)) {
			// Redirect to the login view
			return $this->redirectToLoginPage();
		}
		// Prepare the layout
		$this->prepareLayout($this->layout(), $user);
		// Get the user's schedule
		$schedule = $this->getUserSchedule($user);
		// If the user submitted changes
		if (
			   $this->request->isPost()
			&& ($this->params('mode') === 'edit')
		) {
			// Add the availability to the schedule
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
					'schedule' => $schedule,
				];
			}
			// Persist the changes
			$this->save($schedule);
			// Redirect to the schedule page
			$params = [
				'id'     => $user->getId(),
				'action' => 'schedule',
			];
			return $this->redirect()->toRoute('users', $params);
		}
		// Show the user their schedule
		return [
			'schedule' => $schedule,
			'user' => $user,
		];
	}

	/**
	 * Displays to the user their matches
	 *
	 * @return array|Zend\Http\Response
	 */
	public function matchesAction()
	{
		// If a user is not in session
		$user = $this->currentSessionUser();
		if (!isset($user)) {
			// Redirect to the login view
			return $this->redirectToLoginPage();
		}
		// Prepare the layout
		$this->prepareLayout($this->layout(), $user);
		// Show the user their matches
		return [
			'user' => $user,
		];
	}
}
