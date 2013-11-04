<?php

namespace AbaLookup;

use AbaLookup\Form\LoginForm;
use AbaLookup\Form\ProfileEditForm;
use AbaLookup\Form\RegisterForm;
use AbaLookup\Form\ScheduleForm;
use AbaLookup\Session\Session;
use Zend\View\Model\ViewModel;

/**
 * Controller for user actions
 */
class UsersController extends AbaLookupController
{
	/**
	 * Registers the user or shows a registration form
	 *
	 * Shows the registration form or sends the POST data along to the
	 * API for validation as needed.
	 *
	 * @return array|Zend\Http\Response
	 */
	public function registerAction()
	{
		// Is a user logged in?
		$uid = Session::getUserId();
		if (!is_null($uid)) {
			// Redirect the user to their profile page
			return $this->redirectToUsersRoute($id);
		}
		// Prepare the view layout
		$this->prepareLayout();
		// Get the user type from the URL
		$type = $this->params('type');
		// Create a registration form for the particular
		// type of user that is registering
		$form = new RegisterForm($type);
		// If the user has NOT submitted a POST request
		if (!$this->request->isPost()) {
			// Show the registration form
			return [
				'form' => $form,
				'type' => $type,
			];
		}
		// The user has submitted via POST
		// TODO - Validate Terms of Service
		// TODO - Show previous data to user
		$data = $this->params(); // TODO - Is this correct?
		try {
			$id = $this->getApi('UserAccount')->put(
				$data->fromPost($form::ELEMENT_NAME_EMAIL_ADDRESS),
				$data->fromPost($form::ELEMENT_NAME_PASSWORD),
				$data->fromPost($form::ELEMENT_NAME_DISPLAY_NAME),
				$data->fromPost($form::ELEMENT_NAME_USER_TYPE),
				$data->fromPost($form::ELEMENT_NAME_POSTAL_CODE),
				array_intersect_key(
					$data->fromPost(),
					// Flip this array to get the keys that are valid
					// Only the valid keys remain from the POST data
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
			// Show the user the error message
			return [
				'error' => $e->getMessage(),
				'form'  => $form,
				'type'  => $type,
			];
		}
		Session::setUserId($id);
		// Redirect the user to their profile page
		return $this->redirectToUsersRoute($id);
	}

	/**
	 * Logs the user in
	 *
	 * Sends the POST data along to the API as needed.
	 *
	 * @return array|Zend\Http\Response
	 */
	public function loginAction()
	{
		// Is a user logged in?
		$uid = Session::getUserId();
		if (!is_null($uid)) {
			// Redirect the user to their profile page
			return $this->redirectToUsersRoute($id);
		}
		// Prepare the view layout
		$this->prepareLayout();
		// Create a login form
		$form = new LoginForm();
		// If the user has NOT submitted a POST request
		if (!$this->request->isPost()) {
			// Show the login form
			return [
				'form' => $form,
			];
		}
		// The user has submitted data via POST
		$data = $this->params();
		try {
			$id = $this->getApi('UserAccount')->get([
				'email'    => $data->fromPost($form::ELEMENT_NAME_EMAIL_ADDRESS),
				'password' => $data->fromPost($form::ELEMENT_NAME_PASSWORD),
			]);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			return [
				'error' => $e->getMessage(),
				'form'  => $form,
			];
		}
		// Create a session for the user
		Session::setUserId($id, $form->rememberMe());
		return $this->redirectToUsersRoute($id);
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
		Session::unsetUserId();
		return $this->redirectHome();
	}

	/**
	 * Displays the user's profile
	 *
	 * Edits to the user's profile arrive via POST.
	 *
	 * @return array|Zend\Http\Response
	 */
	public function profileAction()
	{
		try {
			$uid  = Session::getUserId();
			$user = $this->getApi('UserAccount')
			             ->get($uid);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			// The ID is NOT valid
			return $this->redirectToLoginPage();
		}
		// Prepare the layout
		$this->prepareLayout($user);
		$form = new ProfileEditForm($user);
		// If the user has NOT submitted a POST request
		if (!$this->request->isPost()) {
			// Show the edit form
			return [
				'user' => $user,
				'form' => $form,
			];
		}
		// The user has submitted data via POST
		$data = $this->params();
		try {
			$this->getApi('UserAccount')->post($uid, [
				'aba_course'             => $data->fromPost($form::ELEMENT_NAME_ABA_COURSE),
				'certificate_of_conduct' => $data->fromPost($form::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE),
				'display_name'           => $data->fromPost($form::ELEMENT_NAME_DISPLAY_NAME),
				'email'                  => $data->fromPost($form::ELEMENT_NAME_EMAIL_ADDRESS),
				'gender'                 => $data->fromPost($form::ELEMENT_NAME_GENDER),
				'phone_number'           => $data->fromPost($form::ELEMENT_NAME_PHONE_NUMBER),
				'postal_code'            => $data->fromPost($form::ELEMENT_NAME_POSTAL_CODE),
			]);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			// Show the error message
			return [
				'error' => $e->getMessage(),
				'user' => $user,
				'form' => $form,
			];
		}
		// Redirect to the profile page
		return $this->redirectToUsersRoute($uid);
	}

	/**
	 * Displays the user's schedule
	 *
	 * Edits to the schedule arrive via POST.
	 *
	 * @return array|Zend\Http\Response
	 */
	public function scheduleAction()
	{
		try {
			$uid  = Session::getUserId();
			$user = $this->getApi('UserAccount')
			             ->get($uid);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			// The user ID is NOT valid
			return $this->redirectToLoginPage();
		}
		// Prepare the layout
		$this->prepareLayout($user);
		// Get the user's schedules
		$schedules = $this->getApi('Schedule')
		                  ->get(['user_id' => $uid]);
		if ($this->request->isPost()) {
			// Add the availability to the schedule
			$data = $this->params();
			// TODO - Make a PUT request to ScheduleInterval API
			return $this->redirectToUsersRoute($uid, 'schedule');
		}
		// Show the user their schedule
		return [
			'schedules' => $schedules,
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
		try {
			$uid  = Session::getUserId();
			$user = $this->getApi('UserAccount')
			             ->get($uid);
		} catch (Lookup\Api\Exception\InvalidDataException $e) {
			// The user ID is NOT valid
			return $this->redirectToLoginPage();
		}
		// Prepare the layout
		$this->prepareLayout($user);
		// Show the user their matches
		return [
			'user' => $user,
		];
	}
}
