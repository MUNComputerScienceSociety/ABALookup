<?php

namespace AbaLookup\Form;

use
	AbaLookup\Entity\User,
	AbaLookup\Entity\UserType,
	Zend\Filter\Digits,
	Zend\Filter\StringTrim,
	Zend\Form\Exception\DomainException,
	Zend\Form\Form,
	Zend\I18n\Filter\Alnum,
	Zend\Validator\Date as DateValidator,
	Zend\Validator\EmailAddress as EmailAddressValidator,
	Zend\Validator\NotEmpty,
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
	const ELEMENT_NAME_DISPLAY_NAME                 = 'display-name';
	const ELEMENT_NAME_EMAIL_ADDRESS                = 'email-address';
	const ELEMENT_NAME_PASSWORD                     = 'password';
	const ELEMENT_NAME_CONFIRM_PASSWORD             = 'confirm-password';
	const ELEMENT_NAME_PHONE_NUMBER                 = 'phone-number';
	const ELEMENT_NAME_GENDER                       = 'gender';
	const ELEMENT_NAME_ABA_COURSE                   = 'aba-course';
	const ELEMENT_NAME_CERTIFICATE_OF_CONDUCT       = 'certificate-of-conduct';
	const ELEMENT_NAME_CERTIFICATE_OF_CONDUCT_DATE  = 'certificate-of-conduct-date';

	/**
	 * Error message if form is invalid
	 */
	protected $message;

	/**
	 * The type of user this registration form is for
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
				'label' => 'Your display name'
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
		// Confirm password
		$this->add([
			'name' => self::ELEMENT_NAME_CONFIRM_PASSWORD,
			'type' => 'password',
			'attributes' => [
				'id' => self::ELEMENT_NAME_CONFIRM_PASSWORD,
			],
			'options' => [
				'label' => 'Confirm your password'
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
				'label' => 'Your phone number (optional)'
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
					'value' => 0
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
					'checked_value' => TRUE
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
					'checked_value' => TRUE
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
					'label' => 'Date on Certificate of Conduct'
				],
			]);
		}
		// Submit
		$this->add([
			'name' => 'register',
			'type' => 'submit',
			'attributes' => [
				'value' => 'Register'
			],
		]);
	}

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
		$displayName = (new Alnum(/* Allow whitespace */ TRUE))
		               ->filter($this->data[self::ELEMENT_NAME_DISPLAY_NAME]);
		$this->data[self::ELEMENT_NAME_DISPLAY_NAME] = $displayName;
		// Is valid?
		$isValid =    isset($displayName)
		           && (new StringLengthValidator(['min' => User::MINIMUM_LENGTH_DISPLAY_NAME]))
		              ->isValid($displayName)
		           && (new NotEmpty())->isValid($displayName)
		;
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
		$strlen = new StringLengthValidator(['min' => $minlen]);
		// Is valid?
		$isValid =    isset($password, $confirmPassword)
		           && $strlen->isValid($password)
		;
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
			$isValid = (new StringLengthValidator(['min' => User::MINIMUM_LENGTH_PHONE_NUMBER]))
			           ->isValid($phone);
			// Set the message
			if (!$isValid) {
				$this->message = 'The entered phone number is not valid.';
				return FALSE;
			}
		}
		return TRUE;
	}

	public function isCertificateOfConductValid()
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
		// Validate the form
		$this->isValid =    $this->isDisplayNameValid()
		                 && $this->isEmailAddressValid()
		                 && $this->isPasswordValid()
		                 && $this->isPhoneNumberValid()
		                 && $this->isCertificateOfConductValid()
		;
		$this->hasValidated = TRUE;
		return $this->isValid;
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
		return $user;
	}
}
