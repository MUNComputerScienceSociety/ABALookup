<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\User,
	AbaLookup\Entity\UserType,
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
	protected $userType;
	protected $gender;
	protected $abaCourse;
	protected $certificateOfConduct;
	protected $postalCode;

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->displayName          = 'Jane';
		$this->email                = 'jane@email.com';
		$this->password             = 'password';
		$this->phone                = 7095551234;
		$this->userType             = UserType::TYPE_ABA_THERAPIST;
		$this->gender               = 'F';
		$this->abaCourse            = TRUE;
		$this->certificateOfConduct = time();
		$this->postalCode           = 'A1B 3X9';
		$this->user = new User(
			$this->displayName,
			$this->email,
			$this->password,
			$this->userType,
			$this->gender,
			$this->abaCourse,
			$this->certificateOfConduct
		);
		$this->user->setPhone($this->phone);
		$this->user->setPostalCode($this->postalCode);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfConstructorPassedNullDisplayName()
	{
		new User(NULL, $this->email, $this->password, $this->userType);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfConstructorPassedNullEmail()
	{
		new User($this->displayName, NULL, $this->password, $this->userType);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfConstructorPassedNullPassword()
	{
		new User($this->displayName, $this->email, NULL, $this->userType);
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
		$name = 'Mary';
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
		$email = 'somebody@email.com';
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
		$password = 'this is a strong password';
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
	public function testExceptionIsThrownIfNonIntegerPhoneNumberPassed()
	{
		$this->user->setPhone('');
	}

	public function testGetUserType()
	{
		$this->assertEquals($this->userType, $this->user->getUserType());
	}

	/**
	 * @depends testGetUserType
	 */
	public function testSetUserType()
	{
		$this->user->setUserType(UserType::TYPE_PARENT);
		$this->assertEquals($this->user->getUserType(), UserType::TYPE_PARENT);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonStringUserTypeGiven()
	{
		$this->user->setUserType(NULL);
	}

	public function testGetGender()
	{
		$this->assertEquals($this->gender, $this->user->getGender());
	}

	/**
	 * @depends testGetGender
	 */
	public function testSetGender()
	{
		// The set gender function should always return uppercase genders
		$this->assertEquals('F', $this->user->setGender('f')->getGender());
	}

	/**
	 * @depends testGetGender
	 */
	public function testRandomGenderStringIsInterpretedAsNull()
	{
		$this->assertNull($this->user->setGender('foo')->getGender());
	}

	/**
	 * @depends testGetGender
	 */
	public function testGenderCanBeNull()
	{
		$this->user->setGender(NULL);
		$this->assertNull($this->user->getGender());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvalidGenderPassed()
	{
		$this->user->setGender(3);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfBooleanGenderPassed()
	{
		$this->user->setGender(FALSE);
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
	 * @depends testGetAbaCourse
	 */
	public function testAbaCourseCanBeNull()
	{
		$this->user->setAbaCourse(NULL);
		$this->assertNull($this->user->getAbaCourse());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonBoolAbaCoursePassed()
	{
		$this->user->setAbaCourse(3);
	}

	public function testGetCertificateOfConduct()
	{
		$this->assertEquals($this->certificateOfConduct, $this->user->getCertificateOfConduct());
	}

	/**
	 * @depends testGetCertificateOfConduct
	 */
	public function testSetCertificateOfConduct()
	{
		$now = time();
		$this->user->setCertificateOfConduct($now);
		$this->assertEquals($now, $this->user->getCertificateOfConduct());
	}

	/**
	 * @depends testGetCertificateOfConduct
	 */
	public function testSetCertificateOfConductCanBeNull()
	{
		$this->user->setCertificateOfConduct(NULL);
		$this->assertNull($this->user->getCertificateOfConduct());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfCertificateOfConductPassedNonNullAndNonInteger()
	{
		$this->user->setCertificateOfConduct('');
	}

	public function testGetPostalCode()
	{
		$this->assertEquals($this->postalCode, $this->user->getPostalCode());
	}

	/**
	 * @depends testGetPostalCode
	 */
	public function testSetPostalCode()
	{
		$this->postalCode = 'A1B 2C3';
		$this->user->setPostalCode($this->postalCode);
		$this->assertEquals($this->postalCode, $this->user->getPostalCode());
	}

	/**
	 * @depends testGetPostalCode
	 */
	public function testPostalCodeCanBeNull()
	{
		$this->user->setPostalCode(NULL);
		$this->assertNull($this->user->getPostalCode());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionisThrownIfSetPostalCodePassedInteger()
	{
		$this->user->setPostalCode(3);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetPostalCodePassedBoolean()
	{
		$this->user->setPostalCode(FALSE);
	}
}
