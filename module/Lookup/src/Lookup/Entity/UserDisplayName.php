<?php

namespace Lookup\Entity;

class UserDisplayName
{
	use Trait\Id;

	/**
	 * The user to whom the display name belongs
	 *
	 * @var User|NULL
	 */
	private $user;

	/**
	 * The display name
	 *
	 * @var string
	 */
	private $displayName;

	/**
	 * The time at which the display name was created
	 *
	 * @var int
	 */
	private $creationTime;

	/**
	 * @param int $id The ID for the entity.
	 * @param User|NULL $user The user.
	 * @param string $displayName The display name.
	 * @param int $creationTime The time at which this display name was created.
	 * @throws Exception\InvalidArgumentException
	 */
	public function __construct($id, $user, $displayName, $creationTime)
	{
		$this->setId($id)
		     ->setUser($user)
		     ->setDisplayName($displayName)
		     ->setCreationTime($creationTime);
	}

	/**
	 * Set the user for the display name
	 *
	 * Type hinting can't be used becuase NULL is valid
	 *
	 * @param User|NULL $user The user to whom the display name belongs.
	 * @throws Exception\InvalidArgumentException If the type is not valid.
	 * @return self
	 */
	public final function setUser($user)
	{
		if (!is_null($user) && !($user instanceof User)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects either NULL or a User.',
				__METHOD__
			));
		}
		$this->user = $user;
		return $this;
	}

	/**
	 * @param string $displayName The display name.
	 * @throws Exception\InvalidArgumentException If the type is invalid.
	 * @return self
	 */
	public final function setDisplayName($displayName)
	{
		if (!is_string($displayName)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a string.',
				__METHOD__
			));
		}
		$this->displayName = $displayName;
		return $this;
	}

	/**
	 * @param int $creationTime The creation time.
	 * @throws Exception\InvalidArgumentException If the type is invalid.
	 * @return self
	 */
	public final function setCreationTime($creationTime)
	{
		if (!is_int($creationTime)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects a int.',
				__METHOD__
			));
		}
		$this->creationTime = $creationTime;
		return $this;
	}

	/**
	 * @return User|NULL The user to whom the display name belongs.
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return string The display name.
	 */
	public function getDisplayName()
	{
		return $this->displayName;
	}

	/**
	 * @return int The creation time.
	 */
	public function getCreationTime()
	{
		return $this->creationTime;
	}
}
