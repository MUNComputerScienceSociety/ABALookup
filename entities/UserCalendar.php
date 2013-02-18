<?php

#user_id (int)
#id
#enabled (bool)
#name (string)

//UserCalendar.php
/**
 * @Entity @Table(name="user_calendars")
 **/
class UserCalendar
{
	/**
	 * @Id @Column(type="integer") @GeneratedValue
	 **/
	protected $id;
	/**
	 * @OneToOne(targetEntity="User")
	 **/
	protected $user_id;
	/**
	 * @Column(type="boolean")
	 **/
	protected $enabled;
	/**
	 * @Column(type="string")
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