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
 * The form for registering users
 */
class RegisterForm extends Form
{
	/**
	 * Constants for form element IDs and names
	 */
	const ELEMENT_NAME_DISPLAY_NAME     = 'display-name';
	const ELEMENT_NAME_EMAIL_ADDRESS    = 'email-address';
	const ELEMENT_NAME_PASSWORD         = 'password';
	const ELEMENT_NAME_CONFIRM_PASSWORD = 'confirm-password';
	const ELEMENT_NAME_PHONE_NUMBER     = 'phone-number';
	const ELEMENT_NAME_USER_TYPE        = 'user-type';
	const ELEMENT_NAME_SEX              = 'sex';
	const ELEMENT_NAME_ABA_COURSE       = 'aba-course';
	const ELEMENT_NAME_CODE_OF_CONDUCT  = 'code-of-conduct';

	/**
	 * Error message if form is invalid
	 */
	protected $message;

	/**
	 * Construct the register form via factory
	 */
	public function __construct()
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
			],
			'options' => ['label' => $label],
		]);

		// password field
		$label = 'Your password';
		$this->add([
			'name' => self::ELEMENT_NAME_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_PASSWORD,
				'placeholder' => $label,
			],
			'options' => ['label' => $label],
		]);

		// confirm password
		$label = 'Confirm your password';
		$this->add([
			'name' => self::ELEMENT_NAME_CONFIRM_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_CONFIRM_PASSWORD,
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
				'type' => 'tel',
				'placeholder' => $label,
			],
			'options' => ['label' => $label],
		]);

		// user type
		$label = 'Parent or therapist';
		$this->add([
			'name' => self::ELEMENT_NAME_USER_TYPE,
			'type' => 'select',
			'attributes' => ['id' => self::ELEMENT_NAME_USER_TYPE],
			'options' => [
				'label' => $label,
				'value_options' => [
					0 => 'Parent',
					1 => 'Therapist',
				],
			],
		]);

		// sex
		$label = 'Sex';
		$this->add([
			'name' => self::ELEMENT_NAME_SEX,
			'type' => 'select',
			'attributes' => ['id' => self::ELEMENT_NAME_SEX],
			'options' => [
				'label' => $label,
				'value_options' => [
					'Undisclosed',
					'M' => 'Male',
					'F' => 'Female',
				],
			],
		]);

		// ABA course
		$label = 'ABA course';
		$this->add([
			'name' => self::ELEMENT_NAME_ABA_COURSE,
			'type' => 'checkbox',
			'attributes' => ['id' => self::ELEMENT_NAME_ABA_COURSE],
			'options' => [
				'label' => $label,
				'checked_value' => TRUE
			],
		]);

		// code of conduct
		$label = 'Code of conduct';
		$this->add([
			'name' => self::ELEMENT_NAME_CODE_OF_CONDUCT,
			'type' => 'checkbox',
			'attributes' => ['id' => self::ELEMENT_NAME_CODE_OF_CONDUCT],
			'options' => [
				'label' => $label,
				'checked_value' => TRUE
			],
		]);

		// submit button
		$this->add([
			'name' => 'register',
			'type' => 'submit',
			'attributes' => ['value' => 'Register'],
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
		$displayName     = $this->data[self::ELEMENT_NAME_DISPLAY_NAME];
		$email           = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$password        = $this->data[self::ELEMENT_NAME_PASSWORD];
		$confirmPassword = $this->data[self::ELEMENT_NAME_CONFIRM_PASSWORD];
		$phone           = $this->data[self::ELEMENT_NAME_PHONE_NUMBER];
		$userType        = $this->data[self::ELEMENT_NAME_USER_TYPE];

		if (!$notEmpty->isValid($displayName)
		    || !$alpha->isValid($displayName)
		    || !$oneChar->isValid($displayName)
		) {
			// (is empty) OR (is not letter or space) OR (is less than on char)
			$this->message = "You must enter a display name contining only characters.";
		} elseif (!$emailAddress->isValid($email)) {
			$this->message = "You must provide a valid email address.";
		} elseif (!$minPasswordChars->isValid($password)) {
			$this->message = sprintf(
				"Your password must be at least %d characters in length.",
				User::MINIMUM_PASSWORD_LENGTH
			);
		} elseif ($password !== $confirmPassword) {
			$this->message = "You must confirm your password.";
		} elseif ($notEmpty->isValid($phone)
		          && (!$allDigits->isValid($phone) || !$minPhoneChars->isValid($phone))
		) {
			// user entered a phone number and (it is not digits or not long enough)
			$this->message = "The phone number provided is not valid.";
		} elseif (!$notEmpty->isValid($userType)) {
			$this->message = "You need to specify either parent or therapist";
		} else {
			// all good
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
	 * Return the new {@code User} from the form
	 */
	public function getUser()
	{
		if (!$this->hasValidated || !$this->isValid) {
			// form has not been validated
			// OR is has been validated and is not valid
			return NULL;
		}

		// aliases
		$displayName   = $this->data[self::ELEMENT_NAME_DISPLAY_NAME];
		$email         = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$password      = $this->data[self::ELEMENT_NAME_PASSWORD];
		$phone         = $this->data[self::ELEMENT_NAME_PHONE_NUMBER];
		$userType      = $this->data[self::ELEMENT_NAME_USER_TYPE];
		$sex           = $this->data[self::ELEMENT_NAME_SEX];
		$abaCourse     = $this->data[self::ELEMENT_NAME_ABA_COURSE];
		$codeOfConduct = $this->data[self::ELEMENT_NAME_CODE_OF_CONDUCT];

		// create and return a new user
		$user = new User(
			$displayName,
			$email,
			$password,
			(bool) $userType,
			$sex,
			(bool) $abaCourse,
			(bool) $codeOfConduct
		);
		if ($phone) {
			// the user entered a phone number
			$user->setPhone($phone);
		}
		return $user;
	}
}
