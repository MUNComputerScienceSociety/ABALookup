<?php

namespace Lookup\Entity;

class ScheduleInterval
{
	use Trait\Id;

	/**
	 * The start time for this interval
	 *
	 * @var int
	 */
	private $startTime;

	/**
	 * The end time for this interval
	 *
	 * @var int
	 */
	private $endTime;

	/**
	 * The weekday of this interval
	 *
	 * @var int
	 */
	private $weekday;

	/**
	 * Constructor
	 *
	 * @param int $id The ID for the entity.
	 * @param int $startTime The start time for the interval.
	 * @param int $endTime The end time for the interval.
	 * @param int $weekday The weekday of this interval.
	 * @throws Exception\InvalidArgumentException
	 */
	public function __construct($id, $startTime, $endTime, $weekday)
	{
		$this->setId($id)
		     ->setStartTime($startTime)
		     ->setEndTime($endTime)
		     ->setWeekday($weekday);
	}

	/**
	 * @param int $startTime The start time for the interval.
	 * @throws Exception\InvalidArgumentException If the start time is not an int.
	 * @return self
	 */
	public final function setStartTime($startTime)
	{
		if (!is_int($startTime)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects an int.',
				__METHOD__
			));
		}
		$this->startTime = $startTime;
		return $this;
	}

	/**
	 * @param int $endTime The end time for the interval.
	 * @throws Exception\InvalidArgumentException If the end time is not an int.
	 * @return self
	 */
	public final function setEndTime($endTime)
	{
		if (!is_int($endTime)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects an int.',
				__METHOD__
			));
		}
		$this->endTime = $endTime;
		return $this;
	}

	/**
	 * @param int $weekday The weekday for the interval.
	 * @throws Exception\InvalidArgumentException If the weekday is not an int.
	 * @return self
	 */
	public final function setWeekday($weekday)
	{
		if (!is_int($weekday)) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects an int.',
				__METHOD__
			));
		}
		$this->weekday = $weekday;
		return $this;
	}

	/**
	 * @return int The start time for the interval.
	 */
	public function getStartTime()
	{
		return $this->startTime;
	}

	/**
	 * @return int The end time for the interval.
	 */
	public function getEndTime()
	{
		return $this->endTime;
	}

	/**
	 * @return int The weekday of this interval.
	 */
	public function getWeekday()
	{
		return $this->weekday;
	}
}
