<?php

namespace AbaLookup\Form;

use
	AbaLookup\Entity\User,
	AbaLookup\Entity\UserType
;

/**
 * The form for registering users
 */
class RegisterForm extends AbstractBaseForm
{
	/**
	 * The user type for this form.
	 *
	 * @see UserType
	 */
	protected $userType;

	/**
	 * Constructor
	 *
	 * @param string $userType The type of the user registering.
	 */
	public function __construct($userType)
	{
		parent::__construct();
		$this->userType = $userType;
		// Display name
		$this->add([
			'name' => self::ELEMENT_NAME_DISPLAY_NAME,
			'type' => 'text',
			'attributes' => [
				'id' => self::ELEMENT_NAME_DISPLAY_NAME,
			],
			'options' => [
				'label' => 'Your display name',
			],
		]);
		// Email address
		$this->add([
			'name' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			'type' => 'email',
			'attributes' => [
				'id' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			],
			'options' => [
				'label' => 'Your email address',
			],
		]);
		// Password field
		$this->add([
			'name' => self::ELEMENT_NAME_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_PASSWORD,
			],
			'options' => [
				'label' => 'Your password',
			],
		]);
		// Confirm password
		$this->add([
			'name' => self::ELEMENT_NAME_CONFIRM_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_CONFIRM_PASSWORD,
			],
			'options' => [
				'label' => 'Confirm your password',
			],
		]);
		// Phone number
		$this->add([
			'name' => self::ELEMENT_NAME_PHONE_NUMBER,
			'type' => 'text',
			'attributes' => [
				'id'   => self::ELEMENT_NAME_PHONE_NUMBER,
				'type' => 'tel',
			],
			'options' => [
				'label' => 'Your phone number (optional)',
			],
		]);
		// Show therapist-only fields?
		if ($userType === UserType::TYPE_ABA_THERAPIST) {
			// Gender
			$this->add([
				'name' => self::ELEMENT_NAME_GENDER,
				'type' => 'select',
				'options' => [
					'label'         => 'Gender',
					'value_options' => [
						'Undisclosed',
						'M' => 'Male',
						'F' => 'Female',
					],
				],
				'attributes' => [
					'id'    => self::ELEMENT_NAME_GENDER,
					'value' => 0,
				],
			]);
			// ABA course
			$this->add([
				'name' => self::ELEMENT_NAME_ABA_COURSE,
				'type' => 'checkbox',
				'attributes' => [
					'id' => self::ELEMENT_NAME_ABA_COURSE
				],
				'options' => [
					'label'         => 'Completed ABA course',
					'checked_value' => TRUE,
				],
			]);
			// Certificate of Conduct
			$this->add([
				'name' => self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT,
				'type' => 'checkbox',
				'attributes' => [
					'id' => self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT
				],
				'options' => [
					'label'         => 'Certificate of Conduct',
					'checked_value' => TRUE,
				],
			]);
			// Certificate of Conduct Date
			$this->add([
				'name' => self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE,
				'type' => 'text',
				'attributes' => [
					'id'   => self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE,
					'type' => 'date',
					'max'  => date('Y-m-d'), // Today
				],
				'options' => [
					'label' => 'Date on Certificate of Conduct',
				],
			]);
		}
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
		// Submit
		$this->add([
			'name' => 'register',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Register',
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
		                 && $this->isPasswordValid()
		                 && $this->isPhoneNumberValid()
		                 && $this->isPostalCodeValid()
		                 && $this->isCertificateOfConductValid();
	}

	/**
	 * Returns the new {@code User} from the form fields
	 *
	 * @return User|NULL
	 */
	public function getUser()
	{
		if (!$this->hasValidated || !$this->isValid) {
			return NULL;
		}
		// Data field aliases
		$displayName          = $this->data[self::ELEMENT_NAME_DISPLAY_NAME];
		$email                = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$password             = $this->data[self::ELEMENT_NAME_PASSWORD];
		$phone                = $this->data[self::ELEMENT_NAME_PHONE_NUMBER];
		$gender               = $this->data[self::ELEMENT_NAME_GENDER];
		$abaCourse            = $this->data[self::ELEMENT_NAME_ABA_COURSE];
		$certificateOfConduct = $this->data[self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT];
		$postalCode           = $this->data[self::ELEMENT_NAME_POSTAL_CODE];
		// Create and return a new user
		$user = new User(
			$displayName,
			$email,
			$password,
			$this->userType,
			$gender,
			$abaCourse !== NULL ? (bool) $abaCourse : $abaCourse,
			$certificateOfConduct
		);
		if ($phone) {
			// The user entered a phone number
			$user->setPhone((int) $phone);
		}
		if ($postalCode) {
			// The user entered their postal code
			$user->setPostalCode($postalCode);
		}
		return $user;
	}
}
