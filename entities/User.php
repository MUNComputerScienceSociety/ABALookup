<?php

#id
#email (string)
#password (string)
#therapist (bool)
#sex (enum)
#phone (string)
#code_of_conduct (bool)
#ABA_course (bool)
#verified (bool)

//User.php
/**
 * @Entity @Table(name="users")
 **/
class User
{
	/**
	 * @Id @Column(type="integer") @GeneratedValue
	 **/
	protected $id;
	/**
	 * @Column(type="string")
	 **/
	protected $email;
	/**
	 * @Column(type="string")
	 **/
	protected $password;
	/**
	 * @Column(type="boolean")
	 **/
	protected $therapist;
	/**
	 * @Column(type="enum")
	 **/
	protected $sex;
	/**
	 * @Column(type="string")
	 **/	
	protected $phone;
	/**
	 * @Column(type="boolean")
	 **/	
	protected $code_of_conduct;
	/**
	 * @Column(type="boolean")
	 **/
	protected $ABA_course;
	/**
	 * @Column(type="boolean")
	 **/
	protected $verified;
	
	public function __construct($email, $password, $therapist, $sex, $phone, $code_of_conduct, $ABA_course)
	{
		$this->email = $email;
		$this->password = $password;
		$this->therapist = $therapist;
		$this->sex = $sex;
		$this->phone = $phone;
		$this->code_of_content = $code_of_content;
		$this->ABA_course = $ABA_course;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
	public function getTherapist()
	{
		return $this->therapist;
	}
	
	public function getSex()
	{
		return $this->sex;
	}
	
	public function getPhone()
	{
		return $this->phone;
	}
	
	public function getCodeOfConduct()
	{
		return $this->code_of_conduct;
	}
	
	public function getABACourse()
	{
		return $this->ABA_course;
	}
	
	public function getVerified()
	{
		return $this->verified;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	public function setTherapist($therapist)
	{
		$this->therapist = $therapist;
	}
	
	public function setSex($sex)
	{
		$this->sex = $sex;
	}
	
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}
	
	public function setCodeOfConduct($code_of_conduct)
	{
		$this->code_of_conduct = $code_of_conduct;
	}
	
	public function setABACourse($ABA_course)
	{
		$this->ABA_course = $ABA_course;
	}
	
	public function setVerified($verified)
	{
		$this->verified = $verified;
	}
}