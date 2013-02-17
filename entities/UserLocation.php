<?php

#user_id (int)
#location_id (int)

//UserLocation.php
class UserLocation
{
	protected $user_id;
	protected $location_id;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getLocationId()
	{
		return $this->location_id;
	}
	
	public function setLocationId($location_id)
	{
		$this->location_id = $location_id;
	}
}