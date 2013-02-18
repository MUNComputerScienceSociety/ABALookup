<?php

namespace ABALookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 **/
class User{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 **/
	protected $id;
	/**
	 * @ORM\Column(type="string", unique=true)
	 **/
	protected $email;
	/**
	 * @ORM\Column(type="string")
	 **/
	protected $password;
	/**
	 * @ORM\Column(type="boolean")
	 **/
	protected $therapist = false;
	/**
	 * @ORM\Column(type="string", nullable=false)
	 **/
	protected $sex; // :D
	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 **/	
	protected $code_of_conduct;
	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 **/
	protected $ABA_course;
	/**
	 * @ORM\Column(type="boolean")
	 **/
	protected $moderator = false;
	/**
	 * @ORM\Column(type="boolean")
	 **/
	protected $verified = false;
	
	public function __construct($email, $password, $therapist, $sex, $phone, $code_of_conduct, $ABA_course){
		$this->email = $email;
		$this->password = $password;
		$this->therapist = $therapist;
		$this->sex = $sex;
		$this->code_of_conduct = $code_of_conduct;
		$this->ABA_course = $ABA_course;
		$this->verified = false;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function getPassword(){
		return $this->password;
	}
	
	public function getTherapist(){
		return $this->therapist;
	}
	
	public function getSex(){
		return $this->sex;
	}
	
	public function getCodeOfConduct(){
		return $this->code_of_conduct;
	}
	
	public function getABACourse(){
		return $this->ABA_course;
	}
	
	public function getVerified(){
		return $this->verified;
	}
	
	public function getModerator(){
		return $this->verified;
	}
	
	public function setEmail($email){
		$this->email = $email;
	}
	
	public function setPassword($password){
		$this->password = $password;
	}
	
	public function setTherapist($therapist){
		$this->therapist = $therapist;
	}
	
	public function setSex($sex){
		$this->sex = $sex;
	}
	
	public function setCodeOfConduct($code_of_conduct){
		$this->code_of_conduct = $code_of_conduct;
	}
	
	public function setABACourse($ABA_course){
		$this->ABA_course = $ABA_course;
	}
	
	public function setVerified($verified){
		$this->verified = $verified;
	}
	
	public function setModerator($moderator){
		$this->moderator = $moderator;
	}
}
