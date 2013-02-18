<?php

#id
#name (string)
#enabled (bool)

// location.php
/**
 * @Entity @Table(name="locations")
 **/
class Location
{
	/**
	 * @Id @Column(type="integer") @GeneratedValue
	 **/
	protected $id;
	/**
	 * @Column(type="string")
	 **/
	protected $name;
	/**
	 * @Column(type="boolean")
	 **/
	protected $enabled.
	
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