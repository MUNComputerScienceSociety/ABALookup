<?php

namespace AbaLookup\Entity;

use
	Doctrine\Common\Collections\ArrayCollection,
	Doctrine\ORM\Mapping\Column,
	Doctrine\ORM\Mapping\Entity as DoctrineEntity,
	Doctrine\ORM\Mapping\GeneratedValue,
	Doctrine\ORM\Mapping\Id,
	Doctrine\ORM\Mapping\JoinColumn,
	Doctrine\ORM\Mapping\JoinTable,
	Doctrine\ORM\Mapping\ManyToMany,
	Doctrine\ORM\Mapping\Table
;

/**
 * @DoctrineEntity
 * @Table(name = "schedule_days")
 *
 * A day in a schedule
 */
class ScheduleDay
{
	/**
	 * The number of hours in a day
	 */
	const HOURS_DAY = 24;

	/**
	 * The number of minutes in half a hour
	 */
	const MINUTES_HALF_HOUR = 30;

	/**
	 * The number of minutes in a hour
	 */
	const MINUTES_HOUR = 60;

	/**
	 * The number used to convert a hour to military time
	 * (e.g. 1hr into the day => 100 in military time)
	 */
	const MILITARY_TIME = 100;

	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue
	 */
	protected $id;

	/**
	 * @Column(type = "integer")
	 *
	 * The integer representation of the day
	 */
	protected $day;

	/**
	 * @Column(type = "string", length = 16)
	 *
	 * The name of the day
	 */
	protected $name;

	/**
	 * @Column(type = "string", length = 3)
	 *
	 * The abbreviated name of the day
	 */
	protected $abbreviation;

	/**
	 * @ManyToMany(targetEntity = "ScheduleInterval", cascade = {"all"}, fetch = "EAGER")
	 * @JoinTable(
	 *     name = "schedule_interval",
	 *     joinColumns = {@JoinColumn(name = "day_id", referencedColumnName = "id")},
	 *     inverseJoinColumns = {@JoinColumn(name = "interval_id", referencedColumnName = "id", unique = TRUE)}
	 * )
	 *
	 * The intervals that exist in the day
	 *
	 * Note the name of the JoinTable is the singular version
	 * of the name of the table for ScheduleInterval.
	 */
	protected $intervals;

	/**
	 * @Column(type = "integer", name = "interval_minutes")
	 *
	 * The number of minutes of an interval
	 *
	 * The length of all the intervals in the day are the same
	 * thus the interval length is defined here instead of inside the
	 * intervals themselves.
	 */
	protected $intervalMinutes;

	/**
	 * Constructor
	 *
	 * Create a new ScheduleDay and fill it with the appropriate intervals.
	 */
	public function __construct($day, $name, $hours = self::HOURS_DAY, $intervalMinutes = self::MINUTES_HALF_HOUR)
	{
		$this->day = $day;
		$this->name = $name;
		$this->abbreviation = substr($name, 0, 3);
		$this->intervals = new ArrayCollection();
		$this->intervalMinutes = $intervalMinutes; // number of minutes in an interval
		// create and add intervals to the day
		$numberOfIntervalsPerHr = self::MINUTES_HOUR / $intervalMinutes; // number of intervals each hr
		$hoursMilitary = $hours * self::MILITARY_TIME;
		for ($hour = 0; $hour < $hoursMilitary; $hour += self::MILITARY_TIME) {
			for ($i = 0; $i < $numberOfIntervalsPerHr; $i++) {
				// calculate start and end military times
				$startTime = $hour + ($i * $intervalMinutes);
				$endMinute = ((($i + 1) * $intervalMinutes) % self::MINUTES_HOUR);
				$endTime = $hour + (($endMinute == 0) ? self::MILITARY_TIME : $endMinute);
				// create an interval
				$interval = new ScheduleInterval($startTime, $endTime);
				// add the interval to this day
				$this->intervals->add($interval);
			}
		}
	}

	/**
	 * Override the default name for the day
	 *
	 * @return ScheduleDay $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Override the default abbreviation for the day
	 *
	 * @return ScheduleDay $this
	 */
	public function setAbbrev($abbreviation)
	{
		$this->abbreviation = $abbreviation;
		return $this;
	}

	/**
	 * Return the integer representation of the day
	 */
	public function getDay()
	{
		return $this->day;
	}

	/**
	 * Return the name of the day
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Return the abbreviated name of the day
	 */
	public function getAbbrev()
	{
		return $this->abbreviation;
	}

	/**
	 * @return integer The number of minutes in an interval for the day
	 */
	public function getIntervalMinutes()
	{
		return $this->intervalMinutes;
	}

	/**
	 * @return ArrayCollection The intervals for the day
	 */
	public function getIntervals()
	{
		return $this->intervals;
	}
}
