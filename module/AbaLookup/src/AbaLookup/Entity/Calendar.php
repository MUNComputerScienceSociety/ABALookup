<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(name="calendar")
 */
class Calendar
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;
	/**
	 * @ORM\OneToOne(targetEntity="User")
	 */
	protected $user;
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $enabled;
	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;

	public function __construct(User $user, $enabled, $name)
	{
		$this->user = $user;
		$this->enabled = $enabled;
		$this->name = $name;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getEnabled()
	{
		return $this->enabled;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setUser($user) {
		$this->user = $user;
	}

	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

}