<?php

namespace Lookup\Entity;

class Account
{
	use Trait\Uuid;

	/**
	 * The user for the account
	 *
	 * @var User
	 */
	private $user;

	/**
	 * The account password
	 *
	 * @var string
	 */
	private $password;

	/**
	 * The password reset code
	 *
	 * @var string|NULL
	 */
	private $passwordResetCode;

	/**
	 * Email address
	 *
	 * @var string
	 */
	private $email;

	/**
	 * Whether the email address has been confirmed
	 *
	 * @var bool
	 */
	private $emailConfirmed;

	/**
	 * The code/URL used to confirm the email address
	 *
	 * @var string|NULL
	 */
	private $emailConfirmCode;

	/**
	 * The access level for the account
	 *
	 * @var int
	 */
	private $accessLevel;

	/**
	 * The version of the Terms of Service that the user has agreed to
	 *
	 * @var int
	 */
	private $termsOfService;

	/**
	 * The time (UNIX timestamp) at which the account was created
	 *
	 * @var int
	 */
	private $creationTime;

	/**
	 * Constructor
	 *
	 * @param string $id The UUID for the account.
	 * @param User $user The user for the account.
	 * @param string $password The password for the account.
	 * @param string|NULL $passwordResetCode The password reset code.
	 * @param string $email The account email address.
	 * @param bool $emailConfirmed Whether the email address has been confirmed.
	 * @param string|NULL $emailConfirmCode The code to confirm the email address.
	 * @param int $accessLevel The access level.
	 * @param int $termsOfService The Terms of Service version the account has agreed to.
	 * @param int $creationTime The time at which the account was created.
	 * @throws Exception\InvalidArgumentException
	 */
	public function __construct($id, User $user, $password, $passwordResetCode, $email, $emailConfirmed, $emailConfirmCode, $accessLevel, $termsOfService, $creationTime)
	{
		$this->setId($id)
		     ->setUser($user)
		     ->setPassword($password)
		     ->setPasswordResetCode($passwordResetCode)
		     ->setEmail($email)
		     ->setEmailConfirmed($emailConfirmed)
		     ->setEmailConfirmCode($emailConfirmCode)
		     ->setAccessLevel($accessLevel)
		     ->setTermsOfService($termsOfService)
		     ->setCreationTime($creationTime);
	}

	/**
	 * @param User $user The user for the account.
	 * @return self
	 */
	public final function setUser(User $user)
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @param string $password The account password.
	 * @throws Exception\InvalidArgumentException If the password is not a string.
	 * @return self
	 */
	public final function setPassword($password)
	{
		if (!is_string($password)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string.',
				__METHOD__
			));
		}
		$this->password = $password;
		return $this;
	}

	/**
	 * @param string|NULL $passwordResetCode The password reset code.
	 * @throws Exception\InvalidArgumentException If the type is invalid.
	 * @return self
	 */
	public final function setPasswordResetCode($passwordResetCode)
	{
		if (!is_string($passwordResetCode) && !is_null($passwordResetCode)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string or NULL value.',
				__METHOD__
			));
		}
		$this->passwordResetCode = $passwordResetCode;
		return $this;
	}

	/**
	 * @param string $email The email address for the account.
	 * @throws Exception\InvalidArgumentException If the email address is not a string.
	 * @return self
	 */
	public final function setEmail($email)
	{
		if (!is_string($email)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string value.',
				__METHOD__
			));
		}
		$this->email = $email;
		return $this;
	}

	/**
	 * @param bool $emailConfirmed Whether the email address has been confirmed.
	 * @throws Exception\InvalidArgumentExceptio If the argument is not a bool value.
	 * @return self
	 */
	public final function setEmailConfirmed($emailConfirmed)
	{
		if (!is_bool($emailConfirmed)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a bool value.',
				__METHOD__
			));
		}
		$this->emailConfirmed = $emailConfirmed;
		return $this;
	}

	/**
	 * @param string|NULL $emailConfirmCode The confirmation code.
	 * @throws Exception\InvalidArgumentException If the type is invalid.
	 * @return self
	 */
	public final function setEmailConfirmCode($emailConfirmCode)
	{
		if (!is_string($emailConfirmCode) && !is_null($emailConfirmCode)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string or NULL value.',
				__METHOD__
			));
		}
		$this->emailConfirmCode = $emailConfirmCode;
		return $this;
	}

	/**
	 * @param int $accessLevel The account access level.
	 * @throws Exception\InvalidArgumentException If the access level is not an int.
	 * @return self
	 */
	public final function setAccessLevel($accessLevel)
	{
		if (!is_int($accessLevel)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects an int.',
				__METHOD__
			));
		}
		$this->accessLevel = $accessLevel;
		return $this;
	}

	/**
	 * @param int $termsOfService The Terms of Service the account has agreed to.
	 * @throws Exception\InvalidArgumentException If the type is invalid.
	 * @return self
	 */
	public final function setTermsOfService($termsOfService)
	{
		if (!is_int($termsOfService)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects an int.',
				__METHOD__
			));
		}
		$this->termsOfService = $termsOfService;
		return self;
	}

	/**
	 * @param int $creationTime The time at which the account was created.
	 * @throws Exception\InvalidArgumentException If the creation time is not an int.
	 * @return self
	 */
	public final function setCreationTime($creationTime)
	{
		if (!is_int($creationTime)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects an int.',
				__METHOD__
			));
		}
		$this->creationTime = $creationTime;
		return $this;
	}

	/**
	 * @return User The user for the account.
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return string The account password.
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @return string|NULL The reset code for the account password.
	 */
	public function getPasswordResetCode()
	{
		return $this->passwordResetCode;
	}

	/**
	 * @return string The account email address.
	 */
	public function getEmail()
	{
		return $email;
	}

	/**
	 * @return bool Whether the account has confirmed the email address.
	 */
	public function isEmailConfirmed()
	{
		return $this->emailConfirmed;
	}

	/**
	 * @return string|NULL The code to confirm the email address.
	 */
	public function getEmailConfirmCode()
	{
		return $this->emailConfirmCode;
	}

	/**
	 * @return int The access level for the account.
	 */
	public function getAccessLevel()
	{
		return $this->accessLevel;
	}

	/**
	 * @return int The Terms of Service the account has agreed to.
	 */
	public function getTermsOfService()
	{
		return $this->termsOfService;
	}

	/**
	 * @return int The time at which this account was created.
	 */
	public function getCreationTime()
	{
		return $this->creationTime;
	}
}
