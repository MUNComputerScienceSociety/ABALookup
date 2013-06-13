<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\User,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the User entity
 */
class UserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Entity\User
	 */
	protected $user;

	/**
	 * User fields
	 */
	protected $displayName;
	protected $email;
	protected $password;
	protected $phone;
	protected $therapist;
	protected $sex;
	protected $abaCourse;
	protected $codeOfConduct;

	/**
	 * Reset the user
	 */
	public function setUp()
	{
		$this->displayName = "Jane";
		$this->email = "jane@email.com";
		$this->password = "password";
		$this->phone = 7095551234;
		$this->therapist = TRUE;
		$this->sex = "F";
		$this->abaCourse = TRUE;
		$this->codeOfConduct = TRUE;
		$this->user = new User(
			$this->displayName,
			$this->email,
			$this->password,
			$this->therapist,
			$this->sex,
			$this->abaCourse,
			$this->codeOfConduct
		);
		$this->user->setPhone($this->phone);
	}

	public function testGetDisplayName()
	{
		$this->assertEquals($this->displayName, $this->user->getDisplayName());
	}

	/**
	 * @depends testGetDisplayName
	 */
	public function testSetDisplyName()
	{
		$name = "Mary";
		$this->user->setDisplayName($name);
		$this->assertEquals($name, $this->user->getDisplayName());
	}

	public function testGetEmail()
	{
		$this->assertEquals($this->email, $this->user->getEmail());
	}

	/**
	 * @depends testGetEmail
	 */
	public function testSetEmail()
	{
		$email = "somebody@email.com";
		$this->user->setEmail($email);
		$this->assertEquals($email, $this->user->getEmail());
	}

	public function testVerifyPassword()
	{
		$this->assertTrue($this->user->verifyPassword($this->password));
	}

	public function testGetPhone()
	{
		$this->assertEquals($this->phone, $this->user->getPhone());
	}

	/**
	 * @depends testGetPhone
	 */
	public function testSetPhone()
	{
		$phone = 709555000;
		$this->user->setPhone($phone);
		$this->assertEquals($phone, $this->user->getPhone());
	}

	public function testGetTherapist()
	{
		$this->assertTrue($this->user->getTherapist());
	}

	/**
	 * @depends testGetTherapist
	 */
	public function testSetTherapist()
	{
		$this->user->setTherapist(FALSE);
		$this->assertFalse($this->user->getTherapist());
	}

	public function testGetSex()
	{
		$this->assertEquals($this->sex, $this->user->getSex());
	}

	/**
	 * @depends testGetSex
	 */
	public function testSetSex()
	{
		$this->user->setSex(NULL);
		$this->assertNull($this->user->getSex());
	}

	public function testGetAbaCourse()
	{
		$this->assertTrue($this->user->getAbaCourse());
	}

	/**
	 * @depends testGetAbaCourse
	 */
	public function testSetAbaCourse()
	{
		$this->user->setAbaCourse(FALSE);
		$this->assertFalse($this->user->getAbaCourse());
	}

	public function testGetCodeOfConduct()
	{
		$this->assertTrue($this->user->getCodeOfConduct());
	}

	/**
	 * @depends testGetCodeOfConduct
	 */
	public function testSetCodeOfConduct()
	{
		$this->user->setCodeOfConduct(FALSE);
		$this->assertFalse($this->user->getCodeOfConduct());
	}

	public function testGetVerified()
	{
		$this->assertFalse($this->user->getVErified());
	}

	/**
	 * @depends testGetVerified
	 */
	public function testSetVerified()
	{
		$this->user->setVerified(TRUE);
		$this->assertTrue($this->user->getVerified());
	}

	public function testGetModerator()
	{
		$this->assertFalse($this->user->getModerator());
	}

	/**
	 * @depends testGetModerator
	 */
	public function testSetModerator()
	{
		$this->user->setModerator(TRUE);
		$this->assertTrue($this->user->getModerator());
	}
}
