<?php

#id
#name (string)
#enabled (bool)

// location.php
class Location
{
	protected $id;
	protected $name;
	protected $enabled.
	
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