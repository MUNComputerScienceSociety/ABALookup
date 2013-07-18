<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity as DoctrineEntity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\Table,
	InvalidArgumentException
;

/**
 * @DoctrineEntity
 * @Table(name = "schedule_intervals")
 *
 * A interval in a schedule
 */
class ScheduleInterval
{
	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue
	 *
	 * A unique identifier
	 */
	protected $id;

	/**
	 * @Column(type = "integer", name = "start_time")
	 *
	 * The start time for this interval (in military time)
	 */
	protected $startTime;

	/**
	 * @Column(type = "integer", name = "end_time")
	 *
	 * The end time for this interval (in military time)
	 */
	protected $endTime;

	/**
	 * @Column(type = "boolean")
	 */
	protected $available;

	/**
	 * Constructor
	 *
	 * Create a new ScheduleInterval that is unavailable by default.
	 * @param int $startTime The start time for the interval.
	 * @param int $endTime The end time for the interval.
	 * @param bool $available Whether the intervals is available (default: FALSE).
	 */
	public function __construct($startTime, $endTime, $available = FALSE)
	{
		if (!isset($startTime, $endTime, $available) || !is_int($startTime) || !is_int($endTime)) {
			throw new InvalidArgumentException();
		}
		if (!is_bool($available)) {
			throw new InvalidArgumentException();
		}
		if ($endTime <= $startTime) {
			throw new InvalidArgumentException();
		}
		$this->startTime = $startTime;
		$this->endTime   = $endTime;
		$this->available = $available;
	}

	/**
	 * Set the availability of the interval
	 *
	 * @param bool $available Whether or not the interval is available.
	 * @return $this
	 */
	public function setAvailability($available)
	{
		if (!isset($available) || !is_bool($available)) {
			throw new InvalidArgumentException();
		}
		$this->available = $available;
		return $this;
	}

	/**
	 * Set this interval as available
	 *
	 * @return $this
	 */
	public function setAvailable()
	{
		$this->available = TRUE;
		return $this;
	}

	/**
	 * Set this interval as unavailable
	 *
	 * @return $this
	 */
	public function setUnavailable()
	{
		$this->available = FALSE;
		return $this;
	}

	/**
	 * Return the ID for the interval
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Return the start time for the interval
	 *
	 * @return int
	 */
	public function getStartTime()
	{
		return $this->startTime;
	}

	/**
	 * Return the end time for the interval
	 *
	 * @return int
	 */
	public function getEndTime()
	{
		return $this->endTime;
	}

	/**
	 * Return whether the interval is available
	 *
	 * @return bool
	 */
	public function getAvailability()
	{
		return $this->available;
	}
}
