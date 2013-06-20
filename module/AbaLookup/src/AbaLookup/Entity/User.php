<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity as DoctrineEntity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\Table,
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
	 * The base of the URL for the user's profile
	 */
	protected $urlBase;

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
	public function __construct($displayName, $email, $password, $therapist, $sex, $abaCourse, $codeOfConduct)
	{
		$this->displayName = $displayName;
		$this->email = $email;
		$this->password = self::$bcrypt->create($password);
		$this->phone = NULL;
		$this->therapist = $therapist;
		$this->sex = $sex;
		$this->abaCourse = $abaCourse;
		$this->codeOfConduct = $codeOfConduct;
		$this->verified = FALSE;
		$this->moderator = FALSE;
	}

	public function setDisplayName($displayName)
	{
		$this->displayName = $displayName;
		return $this;
	}

	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}

	public function setPassword($password)
	{
		$this->password = self::$bcrypt->create($password);
		return $this;
	}

	public function setPhone($phone)
	{
		$this->phone = $phone;
		return $this;
	}

	public function setTherapist($therapist)
	{
		$this->therapist = $therapist;
		return $this;
	}

	public function setSex($sex)
	{
		$this->sex = $sex;
		return $this;
	}

	public function setAbaCourse($abaCourse)
	{
		$this->abaCourse = $abaCourse;
		return $this;
	}

	public function setCodeOfConduct($codeOfConduct)
	{
		$this->codeOfConduct = $codeOfConduct;
		return $this;
	}

	public function setVerified($verified)
	{
		$this->verified = $verified;
		return $this;
	}

	public function setModerator($moderator)
	{
		$this->moderator = $moderator;
		return $this;
	}

	public function setUrlBase($urlBase)
	{
		$this->urlBase = $urlBase;
		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getDisplayName()
	{
		return $this->displayName;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function verifyPassword($password)
	{
		return self::$bcrypt->verify($password, $this->password);
	}

	public function getPhone()
	{
		return $this->phone;
	}

	public function getTherapist()
	{
		return $this->therapist;
	}

	public function getSex()
	{
		return $this->sex;
	}

	public function getAbaCourse()
	{
		return $this->abaCourse;
	}

	public function getCodeOfConduct()
	{
		return $this->codeOfConduct;
	}

	public function getVerified()
	{
		return $this->verified;
	}

	public function getModerator()
	{
		return $this->moderator;
	}

	public function getUrlBase()
	{
		return $this->urlBase;
	}
}

User::init();
