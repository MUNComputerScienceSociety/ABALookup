<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity as DoctrineEntity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\Table,
	InvalidArgumentException,
	Zend\Crypt\Password\Bcrypt
;

/**
 * @DoctrineEntity
 * @Table(name = "users")
 *
 * A user
 */
class User
{
	/**
	 * The minimum length for the user's password
	 */
	const MINIMUM_PASSWORD_LENGTH = 8;

	/**
	 * The minimum length for a phone number
	 *
	 * At minimum, 7 characters allows for phone numbers without
	 * area codes (e.g. 5551234).
	 */
	const MINIMUM_PHONE_NUMBER_LENGTH = 7;

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
	 * @Column(type = "string", name = "display_name")
	 */
	protected $displayName;

	/**
	 * @Column(type = "string", unique = TRUE)
	 */
	protected $email;

	/**
	 * @Column(type = "string")
	 */
	protected $password;

	/**
	 * The user's phone number
	 *
	 * The user can optionally provide their phone number, the
	 * field is NULL by default.
	 *
	 * @Column(type = "string", nullable = TRUE)
	 */
	protected $phone;

	/**
	 * @Column(type = "boolean")
	 */
	protected $therapist;

	/**
	 * The sex of the user
	 *
	 * The user can choose to not dicslose their sex, thus
	 * this field can be NULL. This field is either a 'M' for
	 * male or an 'F' for female.
	 *
	 * @Column(type = "string", nullable = TRUE, length = 1)
	 */
	protected $sex;

	/**
	 * Whether the user has completed their course
	 *
	 * @Column(type = "boolean", name = "aba_course")
	 */
	protected $abaCourse;

	/**
	 * Whether the user has accepted the code of conduct
	 *
	 * @Column(type = "boolean", name = "code_of_conduct")
	 */
	protected $codeOfConduct;

	/**
	 * Whether the user has verified their email address
	 *
	 * @Column(type = "boolean")
	 */
	protected $verified;

	/**
	 * @Column(type = "boolean")
	 */
	protected $moderator;

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
	 */
	public function __construct($displayName,
	                            $email,
	                            $password,
	                            $therapist,
	                            $sex           = NULL,
	                            $abaCourse     = FALSE,
	                            $codeOfConduct = FALSE
	) {
		if (!isset($displayName, $email, $password) || !is_bool($therapist)) {
			throw new InvalidArgumentException();
		}
		$this->displayName   = $displayName;
		$this->email         = $email;
		$this->password      = self::$bcrypt->create($password);
		$this->phone         = NULL;
		$this->therapist     = $therapist;
		$this->sex           = $sex;
		$this->abaCourse     = $abaCourse;
		$this->codeOfConduct = $codeOfConduct;
		$this->verified      = FALSE;
		$this->moderator     = FALSE;
	}

	/**
	 * Set the display name of the user
	 *
	 * @param string $displayName The display name of the user.
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
	 * Set the email address of the user
	 *
	 * @param string $email The email address for the user.
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
	 * Set the password for the user
	 *
	 * The password passed must be in plaintext, as it will
	 * be hashed internally.
	 *
	 * @param string $password The plaintext password for the user.
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
	 * Set the phone number for the user
	 *
	 * @param int $phone The phone number for the user.
	 * @return $this
	 */
	public function setPhone($phone)
	{
		if (!isset($phone) || !is_int($phone)) {
			throw new InvalidArgumentException();
		}
		$this->phone = $phone;
		return $this;
	}

	/**
	 * Set whether or not the user is a therapist
	 *
	 * @param bool $therapist Whether or not the user is a therapist.
	 * @return $this
	 */
	public function setTherapist($therapist)
	{
		if (!isset($therapist) || !is_bool($therapist)) {
			throw new InvalidArgumentException();
		}
		$this->therapist = $therapist;
		return $this;
	}

	/**
	 * Set the sex of the user
	 *
	 * @param string $sex The sex of the user (NULL, 'M', or 'F').
	 * @return $this
	 */
	public function setSex($sex)
	{
		if ($sex !== NULL && $sex !== 'M' && $sex !== 'F') {
			throw new InvalidArgumentException();
		}
		$this->sex = $sex;
		return $this;
	}

	/**
	 * Set whether the user has completed the course
	 *
	 * @param bool $abaCourse Whether or not the user has completed their course.
	 * @return $this
	 */
	public function setAbaCourse($abaCourse)
	{
		if (!isset($abaCourse) || !is_bool($abaCourse)) {
			throw new InvalidArgumentException();
		}
		$this->abaCourse = $abaCourse;
		return $this;
	}

	/**
	 * Set whether the user has agreed to the code of conduct
	 *
	 * @param bool $codeOfConduct Whether the user has agreed to the code of conduct.
	 * @return $this
	 */
	public function setCodeOfConduct($codeOfConduct)
	{
		if (!isset($codeOfConduct) || !is_bool($codeOfConduct)) {
			throw new InvalidArgumentException();
		}
		$this->codeOfConduct = $codeOfConduct;
		return $this;
	}

	/**
	 * Set whether or not the user has been verified
	 *
	 * @param bool $verified Whether or not the user has been verified.
	 * @return $this
	 */
	public function setVerified($verified)
	{
		if (!isset($verified) || !is_bool($verified)) {
			throw new InvalidArgumentException();
		}
		$this->verified = $verified;
		return $this;
	}

	/**
	 * Set if the user is or is not a moderator
	 *
	 * @param bool $moderator Whether the user is a moderator or not.
	 * @return $this
	 */
	public function setModerator($moderator)
	{
		if (!isset($moderator) || !is_bool($moderator)) {
			throw new InvalidArgumentException();
		}
		$this->moderator = $moderator;
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
	 * @return bool
	 */
	public function isTherapist()
	{
		return $this->therapist;
	}

	/**
	 * @return string
	 */
	public function getSex()
	{
		return $this->sex;
	}

	/**
	 * @return bool
	 */
	public function getAbaCourse()
	{
		return $this->abaCourse;
	}

	/**
	 * @return bool
	 */
	public function getCodeOfConduct()
	{
		return $this->codeOfConduct;
	}

	/**
	 * @return bool
	 */
	public function isVerified()
	{
		return $this->verified;
	}

	/**
	 * @return bool
	 */
	public function isModerator()
	{
		return $this->moderator;
	}
}

User::init();
