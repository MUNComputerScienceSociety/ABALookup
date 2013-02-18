<?php

namespace ABALookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(name="calendar")
 **/
class Calendar
{
	/**
	 * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
	 **/
	protected $id;
	/**
	 * @ORM\OneToOne(targetEntity="User")
	 **/
	protected $user_id;
	/**
	 * @ORM\Column(type="boolean")
	 **/
	protected $enabled;
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $name;
	
	public function __construct($user_id, $enabled, $name)
	{
		$this->user_id = $user_id;
		$this->enabled = $enabled;
		$this->name = $name;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getUserId()
	{
		return $this->user_id;
	}
	
	public function getEnabled()
	{
		return $this->enabled;
	}
	
	public function getName()
	{
		return $this->name;
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