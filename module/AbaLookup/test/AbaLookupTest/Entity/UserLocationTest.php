<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\Location,
	AbaLookup\Entity\User,
	AbaLookup\Entity\UserLocation,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the UserLocation entity
 */
class UserLocationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Entity\User
	 */
	protected $user;

	/**
	 * @var AbaLookup\Entity\Location
	 */
	protected $location;

	/**
	 * @var AbaLookup\Entity\UserLocation
	 */
	protected $ul;

	/**
	 * User fields
	 */
	protected $displayName;
	protected $email;
	protected $password;
	protected $therapist;
	protected $sex;
	protected $abaCourse;
	protected $codeOfConduct;

	/**
	 * Location fields
	 */
	protected $name;

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->displayName   = 'Jane';
		$this->email         = 'jane@email.com';
		$this->password      = 'password';
		$this->therapist     = TRUE;
		$this->sex           = 'F';
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
		$this->name     = 'Somewhere';
		$this->location = new Location($this->name);
		$this->ul       = new UserLocation($this->user, $this->location);
	}

	public function testGetUser()
	{
		$this->assertEquals($this->user, $this->ul->getUser());
	}

	/**
	 * @depends testGetUser
	 */
	public function testSetUser()
	{
		$user = new User('John', 'someone@email.com', 'password', FALSE, NULL, FALSE, FALSE);
		$this->assertEquals($user, $this->ul->setUser($user)->getUser());
	}

	public function testGetLocation()
	{
		$this->assertEquals($this->location, $this->ul->getLocation());
	}

	/**
	 * @depends testGetLocation
	 */
	public function testSetLocation()
	{
		$location = new Location('Barçelona');
		$this->assertEquals($location, $this->ul->setLocation($location)->getLocation());
	}
}
