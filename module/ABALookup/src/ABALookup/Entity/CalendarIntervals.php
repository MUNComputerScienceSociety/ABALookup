<?php

namespace ABALookup\Entity;

use
	Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\Entity @ORM\Table(name="calendar_interval")
 **/
class CalendarIntervals
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 **/
	protected $id;
	/**
	 * @ORM\OneToMany(mappedBy="id", targetEntity="Calendar")
	 **/
	protected $calendar;
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $start_time;
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $end_time;
	/**
	 * @ORM\Column(type="integer")
	 **/
	protected $week_day;
	
	public function __construct(Calendar $calendar, $start_time, $end_time, $week_day)
	{
		$this->calendar = $calendar;
		$this->start_time = $start_time;
		$this->end_time = $end_time;
		$this->week_day = $week_day;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getCalendar()
	{
		return $this->calendar;
	}
	
	public function getStartTime()
	{
		return $this->start_time;
	}
	
	public function getEndTime()
	{
		return $this->end_time;
	}
	
	public function getWeekDay()
	{
		return $this->week_day;
	}
	
	public function setCalendar($calendar)
	{
		$this->calendar = $calendar;
	}
	
	public function setStartTime($start_time)
	{
		$this->start_time = $start_time;
	}
	
	public function setEndTime($end_time)
	{
		$this->end_time = $end_time;
	}
	
	public function setWeekDay($week_day)
	{
		$this->week_day = $week_day;
	}
}
