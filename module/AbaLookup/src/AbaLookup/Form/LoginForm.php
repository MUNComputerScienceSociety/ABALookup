<?php

namespace AbaLookup\Form;

use
	AbaLookup\Entity\User,
	Zend\Filter\StringTrim,
	Zend\Validator\EmailAddress as EmailAddressValidator,
	Zend\Validator\StringLength as StringLengthValidator
;

/**
 * The login form for users
 */
class LoginForm extends AbstractBaseForm
{
	/**
	 * Constructor
	 */
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
		// Remember me
		$this->add([
			'name' => self::ELEMENT_NAME_REMEMBER_ME,
			'type' => 'checkbox',
			'attributes' => [
				'id' => self::ELEMENT_NAME_REMEMBER_ME
			],
			'options' => [
				'label' => 'Remember me',
				'checked_value' => TRUE,
			],
		]);
		// Submit
		$this->add([
			'name' => 'login',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Login',
			],
		]);
	}

	/**
	 * Sets the {@code $isValid} property
	 */
	public function setIsValid()
	{
		// Data aliases
		$email    = $this->data[self::ELEMENT_NAME_EMAIL_ADDRESS];
		$password = $this->data[self::ELEMENT_NAME_PASSWORD];
		// Validators
		$emailAddress     = new EmailAddressValidator();
		$minPasswordChars = new StringLengthValidator(['min' => User::MINIMUM_LENGTH_PASSWORD]);
		// Set is valid?
		if (
			   !$emailAddress->isValid($email)
			|| !$minPasswordChars->isValid($password)
		) {
			$this->message = 'The entered credentials are not valid.';
			$this->isValid = FALSE;
		} else {
			$this->isValid = TRUE;
		}
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
