<?php

namespace AbaLookup;

use AbaLookup\Form\LoginForm;
use AbaLookup\Form\RegisterForm;
use AbaLookup\Session\Session;

class AuthController extends AbaLookupController
{
	public function __construct()
	{
		// Is a user logged in?
		$uid = Session::getUserId();
		if (!is_null($uid)) {
			// Redirect the user to their profile page
			$this->redirectToUsersRoute($uid);
			return;
		}
		// Prepare the view layout
		$this->prepareLayout();
	}

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
		Session::setUserId($id, (bool) $data->fromPost($form::ELEMENT_NAME_REMEMBER_ME));
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
}
