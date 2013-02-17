<?php

#calendar_id (int)
#id
#start_time
#end_time
#week_day (int)

//UserCalendarIntervals.php
class UserCalendarIntervals
{
	protected $id;
	protected $calendar_id;
	protected $start_time;
	protected $end_time;
	protected $week_day;
	
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