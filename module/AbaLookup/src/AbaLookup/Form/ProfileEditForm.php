<?php

namespace AbaLookup\Form;

use
	AbaLookup\Entity\User,
	Zend\Filter\StringTrim,
	Zend\Form\Exception\DomainException,
	Zend\Form\Form,
	Zend\Validator\Digits as DigitsValidator,
	Zend\Validator\EmailAddress as EmailAddressValidator,
	Zend\Validator\NotEmpty,
	Zend\Validator\Regex,
	Zend\Validator\StringLength as StringLengthValidator
;

/**
 * The form for editing a user's profile
 */
class ProfileEditForm extends Form
{
	/**
	 * Constants for form element IDs and names
	 */
	const ELEMENT_NAME_DISPLAY_NAME         = 'display-name';
	const ELEMENT_NAME_EMAIL_ADDRESS        = 'email-address';
	const ELEMENT_NAME_OLD_PASSWORD         = 'old-password';
	const ELEMENT_NAME_NEW_PASSWORD         = 'new-password';
	const ELEMENT_NAME_CONFIRM_NEW_PASSWORD = 'confirm-new-password';
	const ELEMENT_NAME_PHONE_NUMBER         = 'phone-number';

	/**
	 * Error message if set
	 */
	protected $message;

	/**
	 * Construct the edit form via factory
	 */
	public function __construct(User $user)
	{
		// super constructor
		parent::__construct();

		// display name
		$label = 'Your display name';
		$this->add([
			'name' => self::ELEMENT_NAME_DISPLAY_NAME,
			'type' => 'text',
			'attributes' => [
				'id' => self::ELEMENT_NAME_DISPLAY_NAME,
				'placeholder' => $label,
				'value' => $user->getDisplayName(),
			],
			'options' => ['label' => $label],
		]);

		// email address
		$label = 'Your email address';
		$this->add([
			'name' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			'type' => 'email',
			'attributes' => [
				'id' => self::ELEMENT_NAME_EMAIL_ADDRESS,
				'placeholder' => $label,
				'value' => $user->getEmail(),
			],
			'options' => ['label' => $label],
		]);

		// old password field
		$label = 'Your old password';
		$this->add([
			'name' => self::ELEMENT_NAME_OLD_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_OLD_PASSWORD,
				'placeholder' => $label,
			],
			'options' => ['label' => $label],
		]);

		// new password field
		$label = 'Set a new password';
		$this->add([
			'name' => self::ELEMENT_NAME_NEW_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_NEW_PASSWORD,
				'placeholder' => $label,
			],
			'options' => ['label' => $label],
		]);

		// confirm new password
		$label = 'Confirm your new password';
		$this->add([
			'name' => self::ELEMENT_NAME_CONFIRM_NEW_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_CONFIRM_NEW_PASSWORD,
				'placeholder' => $label,
			],
			'options' => ['label' => $label],
		]);

		// phone number
		$label = 'Your phone number (optional)';
		$this->add([
			'name' => self::ELEMENT_NAME_PHONE_NUMBER,
			'type' => 'text',
			'attributes' => [
				'id' => self::ELEMENT_NAME_PHONE_NUMBER,
				'placeholder' => $label,
				'type' => 'tel',
				'value' => $user->getPhone(),
			],
			'options' => ['label' => $label],
		]);

		if ($user->getTherapist()) {
			// add therapist specifc form elements
		}

		// submit button
		$this->add([
			'type' => 'submit',
			'name' => 'update',
			'attributes' => ['value' => 'Update information'],
		]);
	}

	/**
	 * Validate the form
	 *
	 * Overrides Zend\Form\Form::isValid to not use Zend\InputFilter\InputFilter.
	 *
	 * @return bool
	 * @throws DomainException
	 */
	public function isValid()
	{
		if ($this->hasValidated) {
			// the form has already been validated
			return $this->isValid;
		}

		// default to invalid
		$this->isValid = FALSE;

		if (!is_array($this->data)) {
			$data = $this->extract();
			if (!is_array($data)) {
				// no data has been set
				throw new DomainException(sprintf(
					'%s is unable to validate as there is no data currently set', __METHOD__
				));
			}
			$this->data = $data;
		}

		// trim all the data
		$strtrim = new StringTrim();
		foreach ($this->data as $k => $v) {
			$this->data[$k] = $strtrim->filter($v);
		}

		// validators
		$oneChar          = new StringLengthValidator(['min' => 1]);
		$minPasswordChars = new StringLengthValidator(['min' => User::MINIMUM_PASSWORD_LENGTH]);
		$minPhoneChars    = new StringLengthValidator(['min' => User::MINIMUM_PHONE_NUMBER_LENGTH]);

		// validators
		$allDigits    = new DigitsValidator(); // if is all digits
		$notEmpty     = new NotEmpty(); // if is not empty
		$alpha        = new Regex('/^[a-zA-Z ]+$/'); // if is letter or space
		$emailAddress = new EmailAddressValidator(); // is is valid email

		// aliases
		$displayName        = $this->data[self::ELEMENT_NAME_DISPLAY_NAME];
		$email              = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$oldPassword        = $this->data[self::ELEMENT_NAME_OLD_PASSWORD];
		$newPassword        = $this->data[self::ELEMENT_NAME_NEW_PASSWORD];
		$confirmNewPassword = $this->data[self::ELEMENT_NAME_CONFIRM_NEW_PASSWORD];
		$phone              = $this->data[self::ELEMENT_NAME_PHONE_NUMBER];

		if (!$notEmpty->isValid($displayName) // is empty
		    || !$alpha->isValid($displayName) // or is not letter or space
		    || !$oneChar->isValid($displayName) // or is less than one character
		) {
			// (is empty) OR (is not letter or space) OR (is less than on char)
			$this->message = "You must enter a display name contining only characters.";
		} elseif (!$emailAddress->isValid($email)) {
			// is not a valid email address
			$this->message = "You must provide a valid email address.";
		} elseif ($notEmpty->isValid($newPassword) && !$minPasswordChars->isValid($newPassword)) {
			// password was entered but is not long enough
			$this->message = sprintf(
				"Your new password must be %d characters in length.",
				User::MINIMUM_PASSWORD_LENGTH
			);
		} elseif ($notEmpty->isValid($newPassword) && ($newPassword !== $confirmNewPassword)) {
			// new password and confirmation do not match
			$this->message = "You must confirm your new password.";
		} elseif ($notEmpty->isValid($newPassword) && !$notEmpty->isValid($oldPassword)) {
			// old password was not entered
			$this->message = "You must enter your old password to change it.";
		} elseif ($notEmpty->isValid($phone)
		          && (!$allDigits->isValid($phone) || !$minPhoneChars->isValid($phone))
		) {
			// phone number was entered and it was (NOT all digits or NOT more than seven characters)
			$this->message = "The phone number provided is not valid.";
		} else {
			// form is valid
			$this->isValid = TRUE;
		}

		$this->hasValidated = TRUE;
		return $this->isValid;
	}

	/**
	 * Return the error message generated by the form
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Update the user's information
	 *
	 * Takes (via reference) the user to update and populates
	 * the fields with the updated data.
	 *
	 * @return bool Whether the update was successful
	 */
	public function updateUser(User &$user)
	{
		if (!$this->hasValidated || !$this->isValid) {
			// the form is invalid
			// or has not yet been validated
			return FALSE;
		}

		// aliases
		$displayName = $this->data[self::ELEMENT_NAME_DISPLAY_NAME];
		$email       = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$oldPassword = $this->data[self::ELEMENT_NAME_OLD_PASSWORD];
		$newPassword = $this->data[self::ELEMENT_NAME_NEW_PASSWORD];
		$phone       = $this->data[self::ELEMENT_NAME_PHONE_NUMBER];

		if ($newPassword) {
			if (!$user->verifyPassword($oldPassword)) {
				// the user entered their password but it is invalid
				$this->message = "The entered password is incorrect.";
				return FALSE;
			} else {
				// the user correctly entered their old password
				// and set a new password
				$user->setPassword($newPassword);
			}
		}
		$user->setDisplayName($displayName);
		$user->setEmail($email);
		if ($phone) {
			// the user entered a phone number
			$user->setPhone($phone);
		}
		return TRUE;
	}
}
