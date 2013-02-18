<?php

namespace ABALookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(name="location")
 **/
class Location
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 **/
	protected $id;
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $name;
	/**
	 * @ORM\Column(type="boolean")
	 **/
	protected $enabled;
	
	public function __construct($name)
	{
		$this->name = $name;
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
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
	}
}
