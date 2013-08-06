<?php

namespace AbaLookupTest\View\Helper;

use
	AbaLookup\Entity\Schedule,
	AbaLookup\Entity\User,
	AbaLookup\View\Helper\ScheduleHelper,
	PHPUnit_Framework_TestCase
;

class ScheduleHelperTest extends PHPUnit_Framework_TestCase
{
	protected $helper;
	protected $schedule;

	public function setUp()
	{
		$this->helper = new ScheduleHelper();
		$this->schedule = new Schedule(new User(
			'John Smith',
			'js@email.com',
			'password',
			FALSE,
			NULL,
			FALSE,
			FALSE
		));
		$this->helper->__invoke($this->schedule);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfGetSelectOptionsForDaysPassedNullIndex()
	{
		$this->helper->getSelectOptionsForDays(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfGetSelectOptionsForDaysPassedNonIntIndex()
	{
		$this->helper->getSelectOptionsForDays('3');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfGetSelectOptionsForTimesPassedNullIndex()
	{
		$this->helper->getSelectOptionsForTimes(NULL);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfGetSelectOptionsForTimesPassedNonIntIndex()
	{
		$this->helper->getSelectOptionsForTimes('3');
	}

	public function testGetSelectOptionsForDaysReturnsString()
	{
		$this->assertInternalType('string', $this->helper->getSelectOptionsForDays());
	}

	public function testGetSelectOptionsForTimesReturnsString()
	{
		$this->assertInternalType('string', $this->helper->getSelectOptionsForTimes());
	}

	public function testRenderScheduleReturnsString()
	{
		$this->assertInternalType('string', $this->helper->renderSchedule());
		$this->assertInternalType('string', $this->helper->renderSchedule(['foo', 'bar']));
	}
}
