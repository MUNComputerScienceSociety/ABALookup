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
	Doctrine\ORM\Mapping\OneToOne,
	Doctrine\ORM\Mapping\Table,
	InvalidArgumentException,
	Traversable,
	Zend\Stdlib\ArrayUtils
;

/**
 * @DoctrineEntity
 * @Table(name = "schedules")
 *
 * A user's schedule
 */
class Schedule
{
	/**
	 * The mapping from day integers to day names
	 */
	protected static $week = [
		0 => "Monday",
		1 => "Tuesday",
		2 => "Wednesday",
		3 => "Thursday",
		4 => "Friday",
		5 => "Saturday",
		6 => "Sunday",
	];

	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue
	 *
	 * A unique identifier
	 */
	protected $id;

	/**
	 * @OneToOne(targetEntity = "User")
	 *
	 * The user to whom the schedule belongs
	 */
	protected $user;

	/**
	 * @Column(type = "boolean")
	 *
	 * Whether the schedule is active
	 */
	protected $enabled;

	/**
	 * @ManyToMany(targetEntity = "ScheduleDay", cascade = {"all"}, fetch = "EAGER")
	 * @JoinTable(
	 *     name = "schedule_day",
	 *     joinColumns = {@JoinColumn(name = "schedule_id", referencedColumnName = "id")},
	 *     inverseJoinColumns = {@JoinColumn(name = "day_id", referencedColumnName = "id", unique = TRUE)}
	 * )
	 *
	 * The days in schedule
	 *
	 * Note the name of the JoinTable is the singular
	 * of the name of the table for ScheduleDay.
	 */
	protected $days;

	/**
	 * Constructor
	 *
	 * Create a new Schedule and fill it with days.
	 */
	public function __construct(User $user, $enabled = TRUE)
	{
		$this->user = $user;
		$this->enabled = $enabled;
		$this->days = new ArrayCollection();
		// add the days to the schedule
		foreach (self::$week as $day => $name) {
			$scheduleDay = new ScheduleDay($day, $name);
			$this->days->set($day, $scheduleDay);
		}
	}

	/**
	 * Set the availability of an interval in the schedule
	 *
	 * {@code $data} must be an array or be Traversable and have 3 keys
	 * (day, start-time, end-time). The day is used to pass
	 * along the call to the appropriate {@code ScheduleDay}
	 * in the collection.
	 *
	 * @param array|Traversable $data The array containing the interval information
	 */
	public function setAvailability($data, $available = TRUE)
	{
		if ($data instanceof Traversable) {
			$data = ArrayUtils::iteratorToArray($data);
		}
		if (!is_array($data)) {
			throw new InvalidArgumentException(sprintf(
				'%s expects an array or Traversable argument; received "%s"',
				__METHOD__,
				(is_object($data) ? get_class($data) : gettype($data))
			));
		}

		// aliases
		$day = $data['day'];
		$startTime = $data['start-time'];
		$endTime = $data['end-time'];

		if ($startTime >= $endTime) {
			throw new InvalidArgumentException(sprintf(
				"The start time cannot be be greater than the end time"
			));
		}
		$scheduleDay = $this->days->get($day);
		$scheduleDay->setAvailability($startTime, $endTime, $available);
	}

	/**
	 * Enable the schedule
	 *
	 * @return Schedule $this
	 */
	public function enable()
	{
		$this->enabled = TRUE;
		return $this;
	}

	/**
	 * Disable the schedule
	 *
	 * @return Schedule $this
	 */
	public function disable()
	{
		$this->enabled = FALSE;
		return $this;
	}

	/**
	 * Return the week for the schedule
	 */
	public function getWeek()
	{
		return self::$week;
	}

	/**
	 * @return integer The number of days in a week for the schedule
	 */
	public function getNumberOfDays()
	{
		return count(self::$week);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getEnabled()
	{
		return $this->enabled;
	}

	/**
	 * @return ArrayCollection The days in the schedule
	 */
	public function getDays()
	{
		return $this->days;
	}
}
