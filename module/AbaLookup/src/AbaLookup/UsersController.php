<?php

namespace AbaLookup;

use AbaLookup\Form\ProfileEditForm;
use AbaLookup\Form\ScheduleForm;
use AbaLookup\Session\Session;

class UsersController extends AbaLookupController
{
	/**
	 * The ID of the user in session
	 */
	protected $uid;

	/**
	 * The user object for the user in session
	 *
	 * @var Lookup\Entity\User
	 */
	protected $user;

	public function action()
	{
		try {
			$this->uid  = Session::getUserId();
			$this->user = $this->getApi('UserAccount')
			                   ->get($uid);
		} catch (\Lookup\Api\Exception\InvalidDataException $e) {
			// The user ID is NOT valid
			$this->redirectToLoginPage();
			return;
		}
		// Prepare the layout
		$this->prepareLayout($this->user);
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
		$form = new ProfileEditForm($this->user);
		// If the user has NOT submitted a POST request
		if (!$this->request->isPost()) {
			// Show the edit form
			return [
				'form' => $form,
				'user' => $this->user,
			];
		}
		// The user has submitted data via POST
		$data = $this->params();
		try {
			$this->getApi('UserAccount')->post($this->uid, [
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
				'form'  => $form,
				'user'  => $this->user,
			];
		}
		// Redirect to the profile page
		return $this->redirectToUsersRoute($this->uid);
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
		// Create the schedule edit form
		$form = new ScheduleForm();
		// Get the user's schedules
		$schedules = $this->getApi('Schedule')
		                  ->get(['user_id' => $this->uid]);
		if ($this->request->isPost()) {
			// Add the availability to the schedule
			// $data = $this->params();
			// TODO - Make a PUT request to ScheduleInterval API
			// return $this->redirectToUsersRoute($this->uid, 'schedule');
		}
		// Show the user their schedule
		return [
			'form' => $form,
			'user' => $this->user,
			'schedules' => $schedules,
		];
	}

	/**
	 * Displays to the user their matches
	 *
	 * @return array|Zend\Http\Response
	 */
	public function matchesAction()
	{
		// Show the user their matches
		return [
			'user' => $this->user,
		];
	}
}
