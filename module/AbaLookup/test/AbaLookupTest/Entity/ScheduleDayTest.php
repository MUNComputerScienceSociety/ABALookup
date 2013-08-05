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
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->day = 6;
		$this->name = "Sunday";
		$this->abbrev = "Sun";
		$this->scheduleDay = new ScheduleDay($this->day, $this->name);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullDayPassedToConstructor()
	{
		new ScheduleDay(NULL, $this->name);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullNamePassedToConstructor()
	{
		new ScheduleDay($this->day, NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfZeroHourDayPassedToConstructor()
	{
		new ScheduleDay($this->day, $this->name, 0);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIntervalLengthOfZeroPassedToConstructor()
	{
		new ScheduleDay($this->day, $this->name, 24 /* hrs */, 0);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonIntegerDayPassedToConstructor()
	{
		new ScheduleDay($this->name, $this->name);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonIntegerHoursPassedToConstructor()
	{
		new ScheduleDay($this->day, $this->name, "24");
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonIntegerIntervalLengthPassedToConstructor()
	{
		new ScheduleDay($this->day, $this->name, 24, "30");
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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetNamePassedNonString()
	{
		$this->scheduleDay->setName(3);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetNamePassedNull()
	{
		$this->scheduleDay->setName(NULL);
	}

	public function testGetAbbrev()
	{
		$this->assertEquals($this->abbrev, $this->scheduleDay->getAbbrev());
	}

	/**
	 * @depends testGetAbbrev
	 */
	public function testSetAbbrev()
	{
		$abbrev = "Abbrev";
		$this->assertEquals($abbrev, $this->scheduleDay->setAbbrev($abbrev)->getAbbrev());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAbbrevPassedEmptyString()
	{
		$this->scheduleDay->setAbbrev('');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExcecptionIsThrownIfSetAbbrevPassedNonString()
	{
		$this->scheduleDay->setAbbrev(3);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAbbrevPassedNull()
	{
		$this->scheduleDay->setAbbrev(NULL);
	}

	public function testSetAvailability()
	{
		$this->scheduleDay->setAvailability(100, 300, TRUE);
		$this->assertTrue($this->scheduleDay->isAvailable(100, 300));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNonIntStartTime()
	{
		$this->scheduleDay->setAvailability('0', 30, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNullStartTime()
	{
		$this->scheduleDay->setAvailability(NULL, 30, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNonIntEndTime()
	{
		$this->scheduleDay->setAvailability(0, '30', TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNullEndTime()
	{
		$this->scheduleDay->setAvailability(0, NULL, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNonBoolAvailability()
	{
		$this->scheduleDay->setAvailability(0, 30, 'TRUE');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNullAvailability()
	{
		$this->scheduleDay->setAvailability(0, 30, NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNonIntStartTime()
	{
		$this->scheduleDay->isAvailable('0', 30);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNullStartTime()
	{
		$this->scheduleDay->isAvailable(NULL, 30);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNonIntEndTime()
	{
		$this->scheduleDay->isAvailable(0, '30');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNullEndTime()
	{
		$this->scheduleDay->isAvailable(0, NULL);
	}

	public function testGetDay()
	{
		$this->assertEquals($this->day, $this->scheduleDay->getDay());
	}

	public function testGetIntervalMinutes()
	{
		$this->assertEquals(30 /* default */, $this->scheduleDay->getIntervalMinutes());
	}

	public function testGetIntervalMinutesReturnsInt()
	{
		$this->assertInternalType('int', $this->scheduleDay->getIntervalMinutes());
	}

	/**
	 * @depends testGetIntervalMinutes
	 */
	public function testIntervalMinutesIsGreaterThanZero()
	{
		$this->assertGreaterThan(0, $this->scheduleDay->getIntervalMinutes());
	}

	public function testGetIntervalsReturnsArrayCollection()
	{
		$intervals = $this->scheduleDay->getIntervals();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $intervals);
	}

	public function testGetIntervalsCount()
	{
		$this->assertCount(48, $this->scheduleDay->getIntervals());
	}

	public function testGetIntervalsContainsScheduleIntervals()
	{
		$intervals = $this->scheduleDay->getIntervals();
		foreach ($intervals as $interval) {
			$this->assertInstanceOf('AbaLookup\Entity\ScheduleInterval', $interval);
		}
	}
}
