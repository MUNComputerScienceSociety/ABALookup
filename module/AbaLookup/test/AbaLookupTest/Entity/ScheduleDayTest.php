<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\ScheduleDay,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the ScheduleDay entity
 */
class ScheduleDayTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Entity\ScheduleDay
	 */
	protected $scheduleDay;

	/**
	 * Fields for the ScheduleDay
	 */
	protected $day;
	protected $name;

	/**
	 * Reset the day
	 */
	public function setUp()
	{
		$this->day = 6;
		$this->name = "Sunday";
		$this->scheduleDay = new ScheduleDay($this->day, $this->name);
	}

	public function testGetDay()
	{
		$this->assertEquals($this->day, $this->scheduleDay->getDay());
	}

	public function testGetName()
	{
		$this->assertEquals($this->name, $this->scheduleDay->getName());
	}

	/**
	 * @depends testGetName
	 */
	public function testSetName()
	{
		$name = "Roasted Chicken Day";
		$this->scheduleDay->setName($name);
		$this->assertEquals($name, $this->scheduleDay->getName());
	}

	public function testSetAbbrev()
	{
		$abbrev = "Abbrev";
		$this->assertEquals($abbrev, $this->scheduleDay->setAbbrev($abbrev)->getAbbrev());
	}

	public function testGetIntervalMinutes()
	{
		$this->assertInternalType('integer', $this->scheduleDay->getIntervalMinutes());
	}

	/**
	 * @depends testGetIntervalMinutes
	 */
	public function testIntervalMinutesIsGreaterThanZero()
	{
		$this->assertGreaterThan(0, $this->scheduleDay->getIntervalMinutes());
	}

	/**
	 * @depends testGetIntervalMinutes
	 */
	public function testGetIntervals()
	{
		$intervals = $this->scheduleDay->getIntervals();
		$intervalMinutes = $this->scheduleDay->getIntervalMinutes();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $intervals);
		$this->assertCount(ScheduleDay::HOURS_DAY * ScheduleDay::MINUTES_HOUR / $intervalMinutes, $intervals);
		foreach ($intervals as $interval) {
			$this->assertInstanceOf('AbaLookup\Entity\ScheduleInterval', $interval);
		}
	}
}
