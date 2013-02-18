<?php

namespace ABALookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(
 *     name="user_location",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="user_idx", columns={"user_id", "location_id"})}
 * )
 **/
class UserLocation{
	/**
	* @ORM\Id 
	* @ORM\Column(type="integer") 
	* @ORM\GeneratedValue
	**/
	protected $id;
	
	/**
	* @ORM\OneToOne(targetEntity="User")
	**/
	protected $user;
	
	/**
	 * @ORM\OneToOne(targetEntity="Location")
	 **/
	protected $location;
	
	public function __construct(User $user, Location $location){
		$this->user = $user;
		$this->location = $location;
	}

	public function getId(){
		return $this->id;
	}
	
	public function getUser(){
		return $this->user;
	}
	
	public function getLocation(){
		return $this->location;
	}

	public function setUser(User $user){
		$this->user = $user;
	}
	
	public function setLocation(Location $location){
		$this->location = $location;
	}
}
