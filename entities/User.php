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
class User
{
	protected $id;
	protected $email;
	protected $password;
	protected $therapist;
	protected $sex;
	protected $phone;
	protected $code_of_conduct;
	protected $ABA_course;
	protected $verified;
	
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