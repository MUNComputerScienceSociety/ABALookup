<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity as DoctrineEntity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\OneToOne,
	Doctrine\ORM\Mapping\Table,
	Doctrine\ORM\Mapping\UniqueConstraint
;

/**
 * @DoctrineEntity
 * @Table(name = "user_locations", uniqueConstraints = {
 *     @UniqueConstraint(name = "user_locations_idx", columns = {"user_id", "location_id"})
 * })
 *
 * A user's location
 */
class UserLocation
{
	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue
	 */
	protected $id;

	/**
	 * @OneToOne(targetEntity = "User")
	 */
	protected $user;

	/**
	 * @OneToOne(targetEntity = "Location")
	 */
	protected $location;

	/**
	 * Constructor
	 */
	public function __construct(User $user, Location $location) {
		$this->user = $user;
		$this->location = $location;
	}

	/**
	 * Set the user
	 *
	 * @param $user The user object.
	 * @return $this
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * Set the location for the user
	 *
	 * @param $location The location object.
	 * @return $this
	 */
	public function setLocation(Location $location)
	{
		$this->location = $location;
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
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return Location
	 */
	public function getLocation()
	{
		return $this->location;
	}
}
