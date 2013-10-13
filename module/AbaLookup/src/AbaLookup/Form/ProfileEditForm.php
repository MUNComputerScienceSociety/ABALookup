<?php

namespace AbaLookup\Form;

use
	AbaLookup\Entity\User
;

/**
 * The form for editing a user profile
 */
class ProfileEditForm extends AbstractBaseForm
{
	/**
	 * Constructor
	 *
	 * @param User $user The user whose profile is being edited
	 */
	public function __construct(User $user)
	{
		parent::__construct();
		// Display name
		$this->add([
			'name' => self::ELEMENT_NAME_DISPLAY_NAME,
			'type' => 'text',
			'attributes' => [
				'id'    => self::ELEMENT_NAME_DISPLAY_NAME,
				'value' => $user->getDisplayName(),
			],
			'options' => [
				'label' => 'Your display name'
			],
		]);
		// Email address
		$this->add([
			'name' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			'type' => 'email',
			'attributes' => [
				'id'    => self::ELEMENT_NAME_EMAIL_ADDRESS,
				'value' => $user->getEmail(),
			],
			'options' => [
				'label' => 'Your email address'
			],
		]);
		// Phone number
		$this->add([
			'name' => self::ELEMENT_NAME_PHONE_NUMBER,
			'type' => 'text',
			'attributes' => [
				'id'    => self::ELEMENT_NAME_PHONE_NUMBER,
				'type'  => 'tel',
				'value' => $user->getPhone(),
			],
			'options' => [
				'label' => 'Your phone number (optional)'
			],
		]);
		// Postal code
		$this->add([
			'name' => self::ELEMENT_NAME_POSTAL_CODE,
			'type' => 'text',
			'attributes' => [
				'id' => self::ELEMENT_NAME_POSTAL_CODE,
			],
			'options' => [
				'label' => 'Postal code (optional)',
			],
		]);
		// Submit btn
		$this->add([
			'type' => 'submit',
			'name' => 'update',
			'attributes' => [
				'value' => 'Update your information'
			],
		]);
	}

	/**
	 * Sets the {@code $isValid} property
	 */
	public function setIsValid()
	{
		$this->isValid =    $this->isDisplayNameValid()
		                 && $this->isEmailAddressValid()
		                 && $this->isPhoneNumberValid();
	}

	/**
	 * Updates the user with their new information
	 *
	 * Takes the user to update and populates the fields with the updated data.
	 *
	 * @param User $user The user to update.
	 * @return bool Whether the update was successful.
	 */
	public function updateUser(User $user)
	{
		if (!$this->hasValidated || !$this->isValid) {
			return FALSE;
		}
		// Aliases
		$displayName = $this->data[self::ELEMENT_NAME_DISPLAY_NAME];
		$email       = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$phone       = $this->data[self::ELEMENT_NAME_PHONE_NUMBER];
		// Update the information
		$user->setDisplayName($displayName);
		$user->setEmail($email);
		if ($phone) {
			// The user entered a phone number
			$user->setPhone((int) $phone);
		}
		return TRUE;
	}
}
