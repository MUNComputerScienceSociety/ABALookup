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
	 * Reset the schedule
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

	public function testGetWeek()
	{
		$this->assertInternalType('array', $this->schedule->getWeek());
	}

	public function testGetNumberOfDays()
	{
		$this->assertEquals(7, $this->schedule->getNumberOfDays());
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
	public function testSetEnabled()
	{
		$this->schedule->disable();
		$this->assertFalse($this->schedule->getEnabled());
		$this->schedule->enable();
		$this->assertTrue($this->schedule->getEnabled());
		$this->schedule->disable()->enable();
		$this->assertTrue($this->schedule->getEnabled());
	}

	public function testGetDays()
	{
		$days = $this->schedule->getDays();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $days);
		$this->assertCount(7, $days);
		foreach ($days as $day) {
			$this->assertInstanceOf('AbaLookup\Entity\ScheduleDay', $day);
		}
	}
}
