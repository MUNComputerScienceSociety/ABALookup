<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\ScheduleInterval,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the ScheduleInterval entity
 */
class ScheduleIntervalTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Entity\ScheduleInterval
	 */
	protected $scheduleInterval;

	/**
	 * Properties for the interval
	 */
	protected $startTime;
	protected $endTime;

	/**
	 * Reset the interval
	 */
	public function setUp()
	{
		$this->startTime = 0;
		$this->endTime = 100;
		$this->scheduleInterval = new ScheduleInterval($this->startTime, $this->endTime);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullPassedAsStartTimeInConstructor()
	{
		new ScheduleInterval(NULL, 100);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullPassedAsEndTimeInConstructor()
	{
		new ScheduleInterval(0, NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonIntPassedAsStartTimeInConstructor()
	{
		new ScheduleInterval(0.5, 100);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonIntPassedAsEndTimeInConstructor()
	{
		new ScheduleInterval(0, 0.5);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfInvalidIntervalConstructed()
	{
		new ScheduleInterval(100, 0);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNonBoolPassedAsAvailabilityInConstructor()
	{
		new ScheduleInterval(0, 100, '');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfNullPassedAsAvailabilityInConstructor()
	{
		new ScheduleInterval(0, 100, NULL);
	}

	public function testGetStartTime()
	{
		$this->assertEquals($this->startTime, $this->scheduleInterval->getStartTime());
	}

	public function testGetEndTime()
	{
		$this->assertEquals($this->endTime, $this->scheduleInterval->getEndTime());
	}

	public function testIsAvailable()
	{
		$this->assertFalse($this->scheduleInterval->isAvailable());
	}

	/**
	 * @depends testIsAvailable
	 */
	public function testSetAvailable()
	{
		$this->scheduleInterval->setAvailable();
		$this->assertTrue($this->scheduleInterval->isAvailable());
	}

	/**
	 * @depends testIsAvailable
	 */
	public function testSetUnavailable()
	{
		$this->scheduleInterval->setUnavailable();
		$this->assertFalse($this->scheduleInterval->isAvailable());
	}

	/**
	 * @depends testIsAvailable
	 */
	public function testSetAvailability()
	{
		$this->scheduleInterval->setAvailability(TRUE);
		$this->assertTrue($this->scheduleInterval->isAvailable());
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNull()
	{
		$this->scheduleInterval->setAvailability(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNonBool()
	{
		$this->scheduleInterval->setAvailability(3);
	}
}
