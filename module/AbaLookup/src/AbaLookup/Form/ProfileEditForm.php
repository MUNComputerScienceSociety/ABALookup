<?php

namespace AbaLookup\Form;

use
	AbaLookup\Entity\User,
	AbaLookup\Entity\UserType
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
				'id'    => self::ELEMENT_NAME_POSTAL_CODE,
				'value' => $user->getPostalCode(),
			],
			'options' => [
				'label' => 'Postal code (optional)',
			],
		]);
		// Show therapist-only fields?
		if ($user->getUserType() === UserType::TYPE_ABA_THERAPIST) {
			// ABA training course
			$this->add([
				'name' => self::ELEMENT_NAME_ABA_COURSE,
				'type' => 'checkbox',
				'attributes' => [
					'id'      => self::ELEMENT_NAME_ABA_COURSE,
					'checked' => $user->getAbaCourse(),
				],
				'options' => [
					'label'         => 'Completed ABA training course',
					'checked_value' => TRUE,
				],
			]);
			// Certificate of Conduct and its date
			$date = $user->getCertificateOfConduct();
			$this->add([
				'name' => self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT,
				'type' => 'checkbox',
				'attributes' => [
					'id'      => self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT,
					'checked' => (bool) $date,
				],
				'options' => [
					'label'         => 'Certificate of Conduct',
					'checked_value' => TRUE,
				],
			]);
			$dateFormElement = [
				'name' => self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE,
				'type' => 'text',
				'attributes' => [
					'id'    => self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE,
					'type'  => 'date',
					'max'   => date('Y-m-d'), // Today
				],
				'options' => [
					'label' => 'Date on Certificate of Conduct',
				],
			];
			if ($date) {
				$dateFormElement['attributes']['value'] = date('Y-m-d', $date);
			}
			$this->add($dateFormElement);
		}
		// Submit btn
		$this->add([
			'type' => 'submit',
			'name' => 'update',
			'attributes' => [
				'value' => 'Update your information',
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
		                 && $this->isPhoneNumberValid()
		                 && $this->isPostalCodeValid()
		                 && $this->isCertificateOfConductValid();
	}

	/**
	 * Updates the user with their new information
	 *
	 * Populates the fields with the updated data.
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
		$abaCourse            = $this->data[self::ELEMENT_NAME_ABA_COURSE];
		$certificateOfConduct = $this->data[self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT];
		$displayName          = $this->data[self::ELEMENT_NAME_DISPLAY_NAME];
		$email                = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$phone                = $this->data[self::ELEMENT_NAME_PHONE_NUMBER];
		$postalCode           = $this->data[self::ELEMENT_NAME_POSTAL_CODE];
		// Update the information
		$user->setAbaCourse($abaCourse !== NULL ? (bool) $abaCourse : $abaCourse)
		     ->setCertificateOfConduct($certificateOfConduct)
		     ->setDisplayName($displayName)
		     ->setEmail($email)
		     ->setPhone($phone ? (int) $phone : NULL)
		     ->setPostalCode($postalCode ? $postalCode : NULL);
		return TRUE;
	}
}
