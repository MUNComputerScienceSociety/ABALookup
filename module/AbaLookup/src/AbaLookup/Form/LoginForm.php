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

	public function __construct()
	{
		parent::__construct();
		// Email address
		$this->add([
			'name' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			'type' => 'email',
			'attributes' => [
				'id' => self::ELEMENT_NAME_EMAIL_ADDRESS,
			],
			'options' => [
				'label' => 'Your email address'
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
				'label' => 'Your password'
			],
		]);
		// Remember me
		$this->add([
			'name' => self::ELEMENT_NAME_REMEMBER_ME,
			'type' => 'checkbox',
			'attributes' => [
				'id' => self::ELEMENT_NAME_REMEMBER_ME
			],
			'options' => [
				'label' => 'Remember me'
			],
		]);
		// Submit
		$this->add([
			'name' => 'login',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Login'
			],
		]);
	}

	/**
	 * Validates the form
	 *
	 * Overrides Zend\Form\Form::isValid.
	 *
	 * @return bool
	 * @throws DomainException
	 */
	public function isValid()
	{
		if ($this->hasValidated) {
			// Validation has already occurred
			return $this->isValid;
		}
		// Default to invalid
		$this->isValid = FALSE;
		if (!is_array($this->data)) {
			$data = $this->extract();
			if (!is_array($data) || !isset($this->data)) {
				// No data set
				throw new DomainException(sprintf(
					'%s is unable to validate as there is no data currently set', __METHOD__
				));
			}
			$this->data = $data;
		}
		// Trim all the data
		$strtrim = new StringTrim();
		foreach ($this->data as $k => $v) {
			$this->data[$k] = $strtrim->filter($v);
		}
		// Alias the data
		$email    = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$password = $this->data[self::ELEMENT_NAME_PASSWORD];
		// Validators
		$emailAddress     = new EmailAddressValidator();
		$minPasswordChars = new StringLengthValidator(['min' => User::MINIMUM_LENGTH_PASSWORD]);
		// Is valid?
		if (!$emailAddress->isValid($email) || !$minPasswordChars->isValid($password)) {
			$this->message      = 'The entered credentials are not valid.';
			$this->hasValidated = TRUE;
			return $this->isValid; // FALSE
		}
		$this->isValid      = TRUE;
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
		return isset($this->message) ? $this->message : '';
	}

	/**
	 * Returns the email address entered
	 *
	 * @return string|NULL
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
	 * @return string|NULL
	 */
	public function getPassword()
	{
		if (!$this->hasValidated || !$this->isValid) {
			return NULL;
		}
		return $this->data[self::ELEMENT_NAME_PASSWORD];
	}

	/**
	 * Returns whether to remember the user session
	 *
	 * @return bool|NULL
	 */
	public function rememberMe()
	{
		if (!$this->hasValidated || !$this->isValid) {
			return NULL;
		}
		return (bool) $this->data[self::ELEMENT_NAME_REMEMBER_ME];
	}
}
