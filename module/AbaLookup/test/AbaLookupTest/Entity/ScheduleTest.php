<?php

namespace AbaLookupTest\Entity;

use
	AbaLookup\Entity\Schedule,
	AbaLookup\Entity\User,
	PHPUnit_Framework_TestCase
;

/**
 * Test methods for the Schedule entity
 */
class ScheduleTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var AbaLookup\Entity\User
	 */
	protected $user;

	/**
	 * @var AbaLookup\Entity\Schedule
	 */
	protected $schedule;

	/**
	 * Resets for isolation
	 */
	public function setUp()
	{
		$this->user = new User(
			'John Smith',
			'js@email.com',
			'password',
			FALSE,
			NULL,
			FALSE,
			FALSE
		);
		$this->schedule = new Schedule($this->user);
	}

	public function testSetAvailability()
	{
		$this->schedule->setAvailability(4, 0, 100, TRUE);
		$this->assertTrue($this->schedule->isAvailable(4, 0, 100));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfStartTimeGreaterThanEndTime()
	{
		$this->schedule->setAvailability(1, 3, 2, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNonIntDay()
	{
		$this->schedule->setAvailability('3', 0, 100, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNullDay()
	{
		$this->schedule->setAvailability(NULL, 1, 2, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNonIntStartTime()
	{
		$this->schedule->setAvailability(3, '0', 100, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNullStartTime()
	{
		$this->schedule->setAvailability(1, NULL, 3, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNonIntEndTime()
	{
		$this->schedule->setAvailability(1, 2, '3', TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNullEndTime()
	{
		$this->schedule->setAvailability(1, 2, NULL, TRUE);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNonBoolAvailability()
	{
		$this->schedule->setAvailability(2, 0, 30, '');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfSetAvailabilityPassedNullAvailability()
	{
		$this->schedule->setAvailability(1, 2, 3, NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNonIntDay()
	{
		$this->schedule->isAvailable('1', 0, 30);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNullDay()
	{
		$this->schedule->isAvailable(NULL, 0, 30);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNonIntStartTime()
	{
		$this->schedule->isAvailable(0, '0', 30);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNullStartTime()
	{
		$this->schedule->isAvailable(0, NULL, 30);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNonIntEndTime()
	{
		$this->schedule->isAvailable(0, 0, '30');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfIsAvailablePassedNullEndTime()
	{
		$this->schedule->isAvailable(0, 0, NULL);
	}

	public function testGetWeekReturnsArray()
	{
		$this->assertInternalType('array', $this->schedule->getWeek());
	}

	public function testGetNumberOfDays()
	{
		$this->assertEquals(count($this->schedule->getWeek()), $this->schedule->getNumberOfDays());
	}

	public function testGetUser()
	{
		$this->assertEquals($this->user, $this->schedule->getUser());
	}

	public function testGetEnabled()
	{
		$this->assertTrue($this->schedule->getEnabled());
	}

	/**
	 * @depends testGetEnabled
	 */
	public function testEnable()
	{
		$this->schedule->enable();
		$this->assertTrue($this->schedule->getEnabled());
	}

	/**
	 * @depends testGetEnabled
	 */
	public function testDisable()
	{
		$this->schedule->disable();
		$this->assertFalse($this->schedule->getEnabled());
	}

	public function testGetDaysReturnsArrayCollection()
	{
		$days = $this->schedule->getDays();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $days);
	}

	public function testGetDaysCount()
	{
		$this->assertCount(7, $this->schedule->getDays());
	}

	public function testGetDaysArrayContainsScheduleDays()
	{
		$days = $this->schedule->getDays();
		foreach ($days as $day) {
			$this->assertInstanceOf('AbaLookup\Entity\ScheduleDay', $day);
		}
	}
}
