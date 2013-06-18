<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity as DoctrineEntity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\Table
;

/**
 * @DoctrineEntity
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
	 */
	public function __construct($name)
	{
		$this->name = $name;
		$this->enabled = TRUE;
	}

	/**
	 * Set the name of the location
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
		return $this;
	}

	/**
	 * Enable the location
	 */
	public function enable()
	{
		$this->enabled = TRUE;
		return $this;
	}

	/**
	 * Disable the location
	 */
	public function disable()
	{
		$this->enabled = FALSE;
		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getEnabled()
	{
		return $this->enabled;
	}
}
