<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\Location,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the Location entity
 */
class LocationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Entity\Location
	 */
	protected $location;

	/**
	 * Location fields
	 */
	protected $name;

	/**
	 * Resets the location
	 */
	public function setUp()
	{
		$this->name = 'Paradise';
		$this->location = new Location($this->name);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullPassedToConstructor()
	{
		new Location(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonStringPassedToConstructor()
	{
		new Location(FALSE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfEmptyStringPassedToConstructor()
	{
		new Location('');
	}

	public function testGetName()
	{
		$this->assertEquals($this->name, $this->location->getName());
	}

	/**
	 * @depends testGetName
	 */
	public function testSetName()
	{
		$name = 'Placentia';
		$this->assertEquals($name, $this->location->setName($name)->getName());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetNamePassedNull()
	{
		$this->location->setName(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetNamePassedNonString()
	{
		$this->location->setName(3);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetNamePassedEmptyString()
	{
		$this->location->setName('');
	}

	public function testIsEnabled()
	{
		$this->assertTrue($this->location->isEnabled());
	}

	/**
	 * @depends testIsEnabled
	 */
	public function testEnable()
	{
		$this->location->enable();
		$this->assertTrue($this->location->isEnabled());
	}

	/**
	 * @depends testIsEnabled
	 */
	public function testDisable()
	{
		$this->location->disable();
		$this->assertFalse($this->location->isEnabled());
	}

	/**
	 * @depends testIsEnabled
	 */
	public function testSetEnabledTrue()
	{
		$this->location->setEnabled(TRUE);
		$this->assertTrue($this->location->isEnabled());
	}

	/**
	 * @depends testIsEnabled
	 */
	public function testSetEnabledFalse()
	{
		$this->location->setEnabled(FALSE);
		$this->assertFalse($this->location->isEnabled());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetEnabledPassedNonBool()
	{
		$this->location->setEnabled('');
	}
}
