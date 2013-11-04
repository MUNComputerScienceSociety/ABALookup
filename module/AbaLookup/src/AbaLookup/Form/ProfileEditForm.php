<?php

namespace AbaLookup\Form;

class ProfileEditForm extends AbstractBaseForm
{
	/**
	 * @param Lookup\Entity\User $user The user whose profile is being edited.
	 */
	public function __construct(Lookup\Entity\User $user)
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
		// Hidden user type field
		$this->add([
			'name' => self::ELEMENT_NAME_USER_TYPE,
			'attributes' => [
				'type'  => 'hidden',
				'value' => $user->getUserType(),
			],
		]);
		// Show therapist-only fields?
		if ($user->getUserType() === self::USER_TYPE_ABA_THERAPIST) {
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
}
