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
		$this->displayName   = "Jane";
		$this->email         = "jane@email.com";
		$this->password      = "password";
		$this->phone         = 7095551234;
		$this->therapist     = TRUE;
		$this->sex           = "F";
		$this->abaCourse     = TRUE;
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfConstructorPassedNullDisplayName()
	{
		new User(NULL, $this->email, $this->password, $this->therapist);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfConstructorPassedNullEmail()
	{
		new User($this->displayName, NULL, $this->password, $this->therapist);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfConstructorPassedNullPassword()
	{
		new User($this->displayName, $this->email, NULL, $this->therapist);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfConstructorPassedNonBooleanTherapist()
	{
		new User($this->displayName, $this->email, $this->password, NULL);
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfEmptyDisplayNameGiven()
	{
		$this->user->setDisplayName('');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullDisplayNameGiven()
	{
		$this->user->setDisplayName(NULL);
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfEmptyEmailGiven()
	{
		$this->user->setEmail('');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullEmailGiven()
	{
		$this->user->setEmail(NULL);
	}

	public function testVerifyPassword()
	{
		$this->assertTrue($this->user->verifyPassword($this->password));
	}

	/**
	 * @depends testVerifyPassword
	 */
	public function testSetPassword()
	{
		$password = "this is a strong password";
		$this->user->setPassword($password);
		$this->assertTrue($this->user->verifyPassword($password));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfEmptyPasswordGiven()
	{
		$this->user->setPassword('');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullPasswordGiven()
	{
		$this->user->setPassword(NULL);
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonIntegerNumberPassed()
	{
		$this->user->setPhone('');
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonBoolTherapistValueGiven()
	{
		$this->user->setTherapist(NULL);
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvalidSexPassed()
	{
		$this->user->setSex(3);
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonBoolAbaCoursePassed()
	{
		$this->user->setAbaCourse(3);
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonBoolCodeOfConduct()
	{
		$this->user->setCodeOfConduct(3);
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonBoolVerified()
	{
		$this->user->setVerified('');
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonBoolModerator()
	{
		$this->user->setModerator(NULL);
	}
}
