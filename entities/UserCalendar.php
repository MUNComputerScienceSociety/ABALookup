<?php

#user_id (int)
#id
#enabled (bool)
#name (string)

//UserCalendar.php
class UserCalendar
{
	protected $id;
	protected $user_id;
	protected $enabled;
	protected $name;
	
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