<?php

namespace AbaLookup\Form;

class RegisterForm extends AbstractBaseForm
{
	/**
	 * @param string $utype The type of the user registering.
	 */
	public function __construct($utype)
	{
		parent::__construct();
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
		// Hidden user type field
		$this->add([
			'name' => self::ELEMENT_NAME_USER_TYPE,
			'attributes' => [
				'type'  => 'hidden',
				'value' => $utype,
			],
		]);
		// Show therapist-only fields?
		if ($utype === self::USER_TYPE_ABA_THERAPIST) {
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
			// Certificate of Conduct date
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
}
