<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\Table,
	InvalidArgumentException,
	Zend\Crypt\Password\Bcrypt
;

/**
 * @Entity
 * @Table(name = "users")
 *
 * A user
 */
class User
{
	/**
	 * The minimum number of characters in a display name
	 */
	const MINIMUM_LENGTH_DISPLAY_NAME = 1;

	/**
	 * The minimum length for passwords
	 */
	const MINIMUM_LENGTH_PASSWORD = 8;

	/**
	 * The minimum length for a phone number
	 *
	 * As a minimum, 7 characters allows for phone numbers without area codes (e.g. 5551234).
	 */
	const MINIMUM_LENGTH_PHONE_NUMBER = 7;

	/**
	 * BCrypt for hashing and verifying password
	 */
	protected static $bcrypt;

	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue
	 */
	protected $id;

	/**
	 * The user's display name
	 *
	 * @Column(type = "string", name = "display_name")
	 */
	protected $displayName;

	/**
	 * The user's email address
	 *
	 * @Column(type = "string", unique = TRUE)
	 */
	protected $email;

	/**
	 * The user's password
	 *
	 * @Column(type = "string")
	 */
	protected $password;

	/**
	 * The user's phone number
	 *
	 * The user can optionally provide their phone number, the
	 * field is NULL by default.
	 *
	 * @Column(type = "integer", nullable = TRUE)
	 */
	protected $phone;

	/**
	 * The type of the user
	 *
	 * @see UserType The possible values for this property.
	 * @Column(type = "string", name = "user_type")
	 */
	protected $userType;

	/**
	 * The gender of the user
	 *
	 * The user can choose to not dicslose their gender, thus
	 * this field can be NULL. This field is either a 'M' for
	 * male or an 'F' for female.
	 *
	 * @Column(type = "string", length = 1, nullable = TRUE)
	 */
	protected $gender;

	/**
	 * Whether the user has completed their course
	 *
	 * This field can be NULL, which represents that the user has not
	 * yet completed the ABA training course.
	 *
	 * @Column(type = "boolean", name = "aba_course", nullable = TRUE)
	 */
	protected $abaCourse;

	/**
	 * The date on which the user last recieved their Certificate of Conduct
	 *
	 * This field can be NULL, which represents that the user has never recieved
	 * their Certificate of Conduct or, in the case of a parent, the field is
	 * not relevant. The field is stored as the number of seconds since the UNIX epoch.
	 *
	 * @Column(type = "integer", name = "certificate_of_conduct", nullable = TRUE)
	 */
	protected $certificateOfConduct;

	/**
	 * The user's postal code
	 *
	 * The user can optionally provide their postal code to be provied the
	 * option of viewing/generating coarse location-based matches. Is they
	 * decide to not provide this field, it will be NULL.
	 *
	 * @Column(type = "string", name = "postal_code", nullable = TRUE)
	 */
	protected $postalCode;

	/**
	 * Initialise static fields
	 *
	 * Called immediately after class definition.
	 */
	public static function init()
	{
		self::$bcrypt = new Bcrypt(['cost' => 5]);
	}

	/**
	 * Constructor
	 *
	 * @param string $displayName The display name for the user.
	 * @param string $email The email address for the user.
	 * @param string $password The user's password in plaintext.
	 * @param string $userType The type of the user.
	 * @param string|NULL $gender The gender of the user.
	 * @param bool|NULL $abaCourse Whether the user has completed the ABA training course.
	 * @param integer|NULL $certificateOfConduct The date on which the user last recieved their Certificate of Conduct.
	 * @throws InvalidArgumentException
	 */
	public function __construct($displayName,
	                            $email,
	                            $password,
	                            $userType,
	                            $gender               = NULL,
	                            $abaCourse            = NULL,
	                            $certificateOfConduct = NULL
	) {
		$this->setDisplayName($displayName);
		$this->setEmail($email);
		$this->setPassword($password);
		$this->setPhone(NULL); // Default
		$this->setUserType($userType);
		$this->setGender($gender);
		$this->setAbaCourse($abaCourse);
		$this->setCertificateOfConduct($certificateOfConduct);
		$this->setPostalCode(NULL); // Default
	}

	/**
	 * Sets the display name of the user
	 *
	 * @param string $displayName The display name of the user.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setDisplayName($displayName)
	{
		if (!isset($displayName) || !is_string($displayName) || !$displayName) {
			throw new InvalidArgumentException();
		}
		$this->displayName = $displayName;
		return $this;
	}

	/**
	 * Sets the email address of the user
	 *
	 * @param string $email The email address for the user.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setEmail($email)
	{
		if (!isset($email) || !is_string($email) || !$email) {
			throw new InvalidArgumentException();
		}
		$this->email = $email;
		return $this;
	}

	/**
	 * Sets the password for the user
	 *
	 * The password must be in plaintext when passed in, as it will
	 * be hashed internally.
	 *
	 * @param string $password The plaintext password for the user.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setPassword($password)
	{
		if (!isset($password) || !is_string($password) || !$password) {
			throw new InvalidArgumentException();
		}
		$this->password = self::$bcrypt->create($password);
		return $this;
	}

	/**
	 * Sets the phone number for the user
	 *
	 * @param int|NULL $phone The phone number for the user.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setPhone($phone)
	{
		if ($phone !== NULL && !is_int($phone)) {
			throw new InvalidArgumentException();
		}
		$this->phone = $phone;
		return $this;
	}

	/**
	 * Sets the user type
	 *
	 * @param string $userType The type of the user.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setUserType($userType)
	{
		if (!isset($userType) || !is_string($userType) || !$userType) {
			throw new InvalidArgumentException();
		}
		$this->userType = $userType;
		return $this;
	}

	/**
	 * Sets the gender of the user
	 *
	 * Given values of 'M', 'F', or NULL, this function will set the gender field
	 * of the user appropriately. Given any other string value, the field will be
	 * set to NULL as a convenience.
	 *
	 * @param string|NULL $gender The gender of the user (NULL, 'M', or 'F').
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setGender($gender)
	{
		if ($gender !== NULL && !is_string($gender)) {
			throw new InvalidArgumentException();
		}
		$gender = strtoupper($gender);
		if ($gender !== 'M' && $gender !== 'F') {
			$this->gender = NULL; // As a convenience
		} else {
			$this->gender = $gender;
		}
		return $this;
	}

	/**
	 * Sets whether the user has completed the ABA training course
	 *
	 * @param bool|NULL $abaCourse Whether or not the user has completed their course.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setAbaCourse($abaCourse)
	{
		if ($abaCourse !== NULL && !is_bool($abaCourse)) {
			throw new InvalidArgumentException();
		}
		$this->abaCourse = $abaCourse;
		return $this;
	}

	/**
	 * Sets the date on which the user last recieved their certificate of conduct
	 *
	 * The {@code $certificateOfConduct} field is stored as the number of seconds
	 * since the UNIX epoch. As such, the given value must be an integer.
	 *
	 * @param int|NULL $certificateOfConduct The date on which the user last recieved their certificate of conduct.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setCertificateOfConduct($certificateOfConduct)
	{
		if ($certificateOfConduct !== NULL && !is_int($certificateOfConduct)) {
			throw new InvalidArgumentException();
		}
		$this->certificateOfConduct = $certificateOfConduct;
		return $this;
	}

	/**
	 * Sets the postal code for the user
	 *
	 * @param string|NULL $postalCode The postal code for the user.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setPostalCode($postalCode)
	{
		if ($postalCode !== NULL && !is_string($postalCode)) {
			throw new InvalidArgumentException();
		}
		$this->postalCode = $postalCode;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getDisplayName()
	{
		return $this->displayName;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Verifies the given password against the password stored for the user
	 *
	 * The given {@code $password} must be in plaintext. Returns TRUE if the
	 * given password is identical to the password for the user, FALSE
	 * if that is not the case.
	 *
	 * @param string $password The plaintext password to be verified.
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	public function verifyPassword($password)
	{
		if (!isset($password) || !is_string($password) || !$password) {
			throw new InvalidArgumentException();
		}
		return self::$bcrypt->verify($password, $this->password);
	}

	/**
	 * @return int
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @return string
	 */
	public function getUserType()
	{
		return $this->userType;
	}

	/**
	 * @return string|NULL
	 */
	public function getGender()
	{
		return $this->gender;
	}

	/**
	 * @return bool|NULL
	 */
	public function getAbaCourse()
	{
		return $this->abaCourse;
	}

	/**
	 * @return int|NULL
	 */
	public function getCertificateOfConduct()
	{
		return $this->certificateOfConduct;
	}

	/**
	 * @return string|NULL
	 */
	public function getPostalCode()
	{
		return $this->postalCode;
	}
}

User::init();
