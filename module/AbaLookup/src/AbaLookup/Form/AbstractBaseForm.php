<?php

namespace AbaLookup\Form;

use
	AbaLookup\Entity\User,
	Zend\Filter\Digits,
	Zend\Filter\StringTrim,
	Zend\Form\Exception\DomainException,
	Zend\Form\Form,
	Zend\I18n\Filter\Alnum as AlnumFilter,
	Zend\Validator\Date as DateValidator,
	Zend\Validator\EmailAddress as EmailAddressValidator,
	Zend\Validator\NotEmpty,
	Zend\Validator\Regex,
	Zend\Validator\StringLength as StrlenValidator
;

/**
 * Abstract base class for custom forms
 */
abstract class AbstractBaseForm extends Form
{
	/**
	 * Constants for form element IDs and names
	 */
	const ELEMENT_NAME_ABA_COURSE                   = 'aba-course';
	const ELEMENT_NAME_CERTIFICATE_OF_CONDUCT       = 'certificate-of-conduct';
	const ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE  = 'certificate-of-conduct-date';
	const ELEMENT_NAME_CONFIRM_PASSWORD             = 'confirm-password';
	const ELEMENT_NAME_DISPLAY_NAME                 = 'display-name';
	const ELEMENT_NAME_EMAIL_ADDRESS                = 'email-address';
	const ELEMENT_NAME_GENDER                       = 'gender';
	const ELEMENT_NAME_PASSWORD                     = 'password';
	const ELEMENT_NAME_PHONE_NUMBER                 = 'phone-number';
	const ELEMENT_NAME_POSTAL_CODE                  = 'postal-code';
	const ELEMENT_NAME_REMEMBER_ME                  = 'remember-me';

	/**
	 * Error message if form is invalid
	 */
	protected $message;

	/**
	 * Returns whether the display name is valid
	 *
	 * Also sets the error message appropriately.
	 *
	 * @return bool
	 */
	protected function isDisplayNameValid()
	{
		// Filter out all but alphanumeric
		$displayName = (new AlnumFilter(/* Allow whitespace */ TRUE))
		               ->filter($this->data[self::ELEMENT_NAME_DISPLAY_NAME]);
		$this->data[self::ELEMENT_NAME_DISPLAY_NAME] = $displayName;
		// Is valid?
		$isValid =    isset($displayName)
		           && (new StrlenValidator(['min' => User::MINIMUM_LENGTH_DISPLAY_NAME]))
		              ->isValid($displayName)
		           && (new NotEmpty())->isValid($displayName);
		// Set the message
		if (!$isValid) {
			$this->message = 'The entered display name is invalid.';
		}
		return $isValid;
	}

	/**
	 * Returns whether the email address is valid
	 *
	 * Also sets the error message appropriately.
	 *
	 * @return bool
	 */
	protected function isEmailAddressValid()
	{
		// Is valid?
		$isValid = (new EmailAddressValidator())
		           ->isValid($this->data[self::ELEMENT_NAME_EMAIL_ADDRESS]);
		// Set the message
		if (!$isValid) {
			$this->message = 'The entered email address is not valid.';
		}
		return $isValid;
	}

	/**
	 * Returns whether the password is valid
	 *
	 * Also sets the error message appropriately if needed.
	 *
	 * @return bool
	 */
	protected function isPasswordValid()
	{
		// Aliases
		$confirmPassword = $this->data[self::ELEMENT_NAME_CONFIRM_PASSWORD];
		$minlen          = User::MINIMUM_LENGTH_PASSWORD;
		$password        = $this->data[self::ELEMENT_NAME_PASSWORD];
		// Validators
		$strlen = new StrlenValidator(['min' => $minlen]);
		// Is valid?
		$isValid =    isset($password, $confirmPassword)
		           && $strlen->isValid($password);
		if (!$isValid) {
			$this->message = sprintf(
				'Password must be at least %d characters long.',
				$minlen
			);
		} elseif ($password !== $confirmPassword) {
			$isValid = FALSE;
			$this->message = 'You must confirm your password.';
		}
		return $isValid;
	}

	/**
	 * Returns whether the phone number is valid
	 *
	 * Also sets the error message appropriately.
	 *
	 * @return bool
	 */
	protected function isPhoneNumberValid()
	{
		// Filter out all but digits
		$phone = (new Digits())->filter($this->data[self::ELEMENT_NAME_PHONE_NUMBER]);
		$this->data[self::ELEMENT_NAME_PHONE_NUMBER] = $phone;
		// Is valid?
		if ((new NotEmpty())->isValid($phone)) {
			$isValid = (new StrlenValidator(['min' => User::MINIMUM_LENGTH_PHONE_NUMBER]))
			           ->isValid($phone);
			// Set the message
			if (!$isValid) {
				$this->message = 'The entered phone number is not valid.';
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * Returns whether the postal code is valid
	 *
	 * Sets the error message appropriately as well. Note that this does not
	 * ensure that the postal code exists, but that it is a postal code.
	 *
	 * @return boolg
	 */
	protected function isPostalCodeValid()
	{
		$postalCode = (new AlnumFilter(/* Allow whitespace */ FALSE))
		              ->filter($this->data[self::ELEMENT_NAME_POSTAL_CODE]);
		$this->data[self::ELEMENT_NAME_POSTAL_CODE] = $postalCode;
		if (!$postalCode) {
			return TRUE;
		}
		$isValid = (new Regex(['pattern' => '/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$/i']))
		           ->isValid($postalCode);
		if (!$isValid) {
			$this->error = 'The entered postal code is not valid.';
		}
		return $isValid;
	}

	/**
	 * Returns whether the Certificate of Conduct is properly set
	 *
	 * Checks three possible cases:
	 * 1. The checkbox to indicate that the user has recieved their Certificate
	 *    of Conduct is checked, and the date entered is valid.
	 * 2. The checkbox is checked, but the entered date is not valid. This will
	 *    set the error message appropriately.
	 * 3. The checkbox was not selected, and in this case, the value should be NULL.
	 *
	 * @return bool Whether the Certificate of Conduct is properly set.
	 */
	protected function isCertificateOfConductValid()
	{
		// Treat the checkbox as a boolean value
		$cert = (bool) $this->data[self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT];
		$date = $this->data[self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE];
		// Is valid?
		$isValid = FALSE;
		// If checkbox was checked and date was valid
		if ($cert) {
			if ((new DateValidator(['format' => 'Y-m-d']))->isValid($date)) {
				$this->data[self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT] = strtotime($date);
				$isValid = TRUE;
			} else {
				// The checkbox was checked but invalid date
				$this->message = 'The entered date is not valid.';
				$isValid = FALSE;
			}
		} else {
			// Checkbox was not checked
			$this->data[self::ELEMENT_NAME_CERTIFICATE_OF_CONDUCT] = NULL;
			$isValid = TRUE;
		}
		return $isValid;
	}

	/**
	 * Returns the error message generated by the form
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return isset($this->message) ? $this->message : '';
	}

	/**
	 * Validates the form
	 *
	 * Overrides Zend\Form\Form::isValid.
	 *
	 * @return bool Is the form valid?
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
				// No data has been set
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
		$this->setIsValid();
		$this->hasValidated = TRUE;
		return $this->isValid;
	}

	/**
	 * Sets the {@code $isValid} property.
	 */
	abstract public function setIsValid();
}
