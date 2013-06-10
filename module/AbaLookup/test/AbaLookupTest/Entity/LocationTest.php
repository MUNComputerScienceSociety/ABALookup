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
	 * Reset the location
	 */
	public function setUp()
	{
		$this->name = "Paradise";
		$this->location = new Location($this->name);
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
		$name = "Placentia";
		$this->assertEquals($name, $this->location->setName($name)->getName());
	}

	public function testGetEnabled()
	{
		$this->assertTrue($this->location->getEnabled());
	}

	/**
	 * @depends testGetEnabled
	 */
	public function testSetEnabled()
	{
		$this->location->enable();
		$this->assertTrue($this->location->getEnabled());
		$this->location->disable();
		$this->assertFalse($this->location->getEnabled());
		$this->location->setEnabled(TRUE);
		$this->assertTrue($this->location->getEnabled());
	}
}
