<?php

#user_id (int)
#location_id (int)

//UserLocation.php
/**
 * @Entity @Table(name="user_locations")
 **/
class UserLocation
{
	/**
	 * @Id @OneToOne(targetEntity="User")
	 **/
	protected $user_id;
	/**
	 * @Column (type="integer")
	 **/
	protected $location_id;
	
	public function __construct($user_id, $location_id)
	{
		$this->user_id = $user_id;
		$this->location_id = $location_id;
	}
	
	public function getUserId()
	{
		return $this->user_id;
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