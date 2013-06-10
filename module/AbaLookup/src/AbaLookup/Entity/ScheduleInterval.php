<?php

namespace AbaLookup\Entity;

use
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity as DoctrineEntity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\Table
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
	 * Create a new ScheduleInterval that is available by default.
	 */
	public function __construct($startTime, $endTime, $available = FALSE)
	{
		if ($endTime <= $startTime) {
			throw new \InvalidArgumentException();
		}
		$this->startTime = $startTime;
		$this->endTime = $endTime;
		$this->available = $available;
	}

	/**
	 * Set the availability of the interval
	 *
	 * @return ScheduleInterval $this
	 */
	public function setAvailability($available)
	{
		$this->available = $available;
		return $this;
	}

	/**
	 * Set this day as available
	 *
	 * @return ScheduleInterval $this
	 */
	public function setAvailable()
	{
		$this->available = TRUE;
		return $this;
	}

	/**
	 * Set this day as unavailable
	 *
	 * @return ScheduleInterval $this
	 */
	public function setUnavailable()
	{
		$this->available = FALSE;
		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getStartTime()
	{
		return $this->startTime;
	}

	public function getEndTime()
	{
		return $this->endTime;
	}

	public function getAvailability()
	{
		return $this->available;
	}
}
