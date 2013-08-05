<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\Table,
	InvalidArgumentException
;

/**
 * @Entity
 * @Table(name = "locations")
 *
 * A possible user location
 */
class Location
{
	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue
	 */
	protected $id;

	/**
	 * @Column(type = "string")
	 */
	protected $name;

	/**
	 * @Column(type = "boolean")
	 */
	protected $enabled;

	/**
	 * Constructor
	 *
	 * @param string $name The name of the location.
	 * @throws InvalidArgumentException
	 */
	public function __construct($name)
	{
		if (!is_string($name) || !$name) {
			throw new InvalidArgumentException();
		}
		$this->name = $name;
		$this->enabled = TRUE;
	}

	/**
	 * Sets the name of the location
	 *
	 * @param string $name The name of the location.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setName($name)
	{
		if (!isset($name) || !is_string($name) || !$name) {
			throw new InvalidArgumentException();
		}
		$this->name = $name;
		return $this;
	}

	/**
	 * Sets the enabled value of the location
	 *
	 * @param bool $enabled Whether this location is enabled.
	 * @throws InvalidArgumentException
	 * @return $this
	 */
	public function setEnabled($enabled)
	{
		if (!is_bool($enabled)) {
			throw new InvalidArgumentException();
		}
		$this->enabled = $enabled;
		return $this;
	}

	/**
	 * Enables the location
	 *
	 * @return $this
	 */
	public function enable()
	{
		$this->enabled = TRUE;
		return $this;
	}

	/**
	 * Disables the location
	 *
	 * @return $this
	 */
	public function disable()
	{
		$this->enabled = FALSE;
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
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function getEnabled()
	{
		return $this->enabled;
	}
}
