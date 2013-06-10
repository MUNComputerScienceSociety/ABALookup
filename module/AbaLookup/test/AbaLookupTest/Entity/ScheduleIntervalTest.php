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
	 * Fields for the interval
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
	public function testExceptionIsThrownIfInvalidInterval()
	{
		new ScheduleInterval(100, 0);
	}

	public function testGetStartTime()
	{
		$this->assertEquals($this->startTime, $this->scheduleInterval->getStartTime());
	}

	public function testGetEndTime()
	{
		$this->assertEquals($this->endTime, $this->scheduleInterval->getEndTime());
	}

	public function testGetAvailability()
	{
		$this->assertFalse($this->scheduleInterval->getAvailability());
	}

	/**
	 * @depends testGetAvailability
	 */
	public function testSetAvailability()
	{
		$this->scheduleInterval->setAvailable();
		$this->assertTrue($this->scheduleInterval->getAvailability());
		$this->scheduleInterval->setUnavailable();
		$this->assertFalse($this->scheduleInterval->getAvailability());
		$this->scheduleInterval->setAvailability(TRUE);
		$this->assertTrue($this->scheduleInterval->getAvailability());
	}
}
