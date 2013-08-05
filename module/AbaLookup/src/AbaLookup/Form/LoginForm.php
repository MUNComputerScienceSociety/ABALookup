<?php

namespace AbaLookup\Form;

use
	AbaLookup\Entity\User,
	Zend\Filter\StringTrim,
	Zend\Form\Exception\DomainException,
	Zend\Form\Form,
	Zend\Validator\EmailAddress as EmailAddressValidator,
	Zend\Validator\StringLength as StringLengthValidator
;

/**
 * The login form for users
 */
class LoginForm extends Form
{
	/**
	 * Constants for form element IDs and names
	 */
	const ELEMENT_NAME_EMAIL_ADDRESS = 'email-address';
	const ELEMENT_NAME_PASSWORD      = 'password';
	const ELEMENT_NAME_REMEMBER_ME   = 'remember-me';

	/**
	 * Error message if form is invalid
	 */
	protected $message;

	/**
	 * Constructs the form via factory
	 */
	public function __construct()
	{
		parent::__construct();

		// email address
		$label = 'Your email address';
		$this->add([
			'name' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			'type' => 'email',
			'attributes' => [
				'id' => self::ELEMENT_NAME_EMAIL_ADDRESS,
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
			],
			'options' => ['label' => $label],
		]);

		// remember me
		$label = 'Remember me';
		$this->add([
			'name' => self::ELEMENT_NAME_REMEMBER_ME,
			'type' => 'checkbox',
			'attributes' => ['id' => self::ELEMENT_NAME_REMEMBER_ME],
			'options' => ['label' => $label],
		]);

		// submit button
		$this->add([
			'name' => 'login',
			'type' => 'submit',
			'attributes' => ['value' => 'Login'],
		]);
	}

	/**
	 * Validates the form
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
			if (!is_array($data) || !isset($this->data)) {
				// no data set
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

		// aliases
		$email    = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$password = $this->data[self::ELEMENT_NAME_PASSWORD];

		// validators
		$emailAddress     = new EmailAddressValidator();
		$minPasswordChars = new StringLengthValidator(['min' => User::MINIMUM_PASSWORD_LENGTH]);

		if (!$emailAddress->isValid($email)) {
			$this->message = "You must enter a valid email address.";
		} elseif (!$minPasswordChars->isValid($password)) {
			$this->message = "The entered credentials are not valid.";
		} else {
			// all good
			$this->isValid = TRUE;
		}

		$this->hasValidated = TRUE;
		return $this->isValid;
	}

	/**
	 * Returns the error message
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Returns the email address entered
	 *
	 * @return string
	 */
	public function getEmailAddress()
	{
		if (!$this->hasValidated || !$this->isValid) {
			return NULL;
		}
		return $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
	}

	/**
	 * Returns the password entered
	 *
	 * @return string
	 */
	public function getPassword()
	{
		if (!$this->hasValidated || !$this->isValid) {
			return NULL;
		}
		return $this->data[self::ELEMENT_NAME_PASSWORD];
	}

	/**
	 * Returns whether to remember
	 *
	 * @return bool
	 */
	public function rememberMe()
	{
		return (bool) $this->data[self::ELEMENT_NAME_REMEMBER_ME];
	}
}
