<?php

#calendar_id (int)
#id
#start_time
#end_time
#week_day (int)

//UserCalendarIntervals.php
/**
 * @Entity @Table(name="user_calendar_intervals")
 **/
class UserCalendarIntervals
{
	/**
	 * @Id @Column(type="integer") @GeneratedValue
	 **/
	protected $id;
	/**
	 * @OneToOne(targetEntity="UserCalendar")
	 **/
	protected $calendar_id;
	/**
	 * @Column(type="datetime")
	 **/
	protected $start_time;
	/**
	 * @Column(type="datetime")
	 **/
	protected $end_time;
	/**
	 * @Column(type="integer")
	 **/
	protected $week_day;
	
	public function __construct($calendar_id, $start_time, $end_time, $week_day)
	{
		$this->calendar_id = $calendar_id;
		$this->start_time = $start_time;
		$this->end_time = $end_time;
		$this->week_day = $week_day;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getCalendarId()
	{
		return $this->calendar_id;
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