<?php

namespace AbaLookup\View\Helper;

use
	AbaLookup\Entity\Schedule as ScheduleEntity,
	DateTime,
	InvalidArgumentException,
	Zend\View\Helper\AbstractHelper as AbstractViewHelper
;

class Schedule extends AbstractViewHelper
{
	/**
	 * HTML form option elements for each day in the schedule
	 */
	protected $dayOptions;

	/**
	 * HTML form option elements for each time interval in the schedule
	 */
	protected $timeOptions;

	/**
	 * The rendered schedule HTML
	 */
	protected $html = NULL;

	/**
	 * Generate the HTML for the schedule and "cache" it
	 *
	 * This will/should be called before {@code render()}
	 * and thus the HTML will be generated here, and
	 * returned by {@code render()}. This method will be
	 * called via {@code __invoke()} which passes along a schedule.
	 *
	 * @param AbaLookup\Entity\Schedule $schedule
	 */
	protected function render(ScheduleEntity $schedule)
	{
		// has the schedule already been rendered
		// if so, return the cached HTML
		if (isset($this->html)) {
			return $this->html;
		}
		// the days in the schedule
		$days = $schedule->getDays()->toArray();
		$i = 0;
		// start with the headers for the leftmost
		// column which holds the time intervals
		$tableHeaders = "<th></th>";
		// array of the table rows for each interval
		$tableRows = [];

		foreach ($days as $day) {

			// the form option for the day
			$value = $day->getDay();
			$name = $day->getName();
			$abbrev = $day->getAbbrev();
			$this->dayOptions .= '<option value="' . $value . '">' . $name . '</option>' . PHP_EOL;
			// the header for the day
			$tableHeaders .= "<th>{$abbrev}</th>" . PHP_EOL;
			// all the intervals
			$intervals = $day->getIntervals()->toArray();
			$intervalsCount = count($intervals);
			$j = 0;

			foreach ($intervals as $interval) {
				$military = $interval->getStartTime();
				// only generate time options once
				// also only populate the leftmost column once
				if ($i == 0) {
					// pad the military time with zeros on the left and format the time for humans
					$time = (new DateTime(str_pad($military, 4, '0', STR_PAD_LEFT)))->format('h:i A');
					$time = ltrim($time, "0");
					$this->timeOptions .= "<option value=\"{$military}\">{$time}</option>" . PHP_EOL;
					$tableRows[$j] = "<td>{$time}</td>" . PHP_EOL;
				}
				// add a cell to the appropriate row in the table, and wrap
				// around once all the days (columns) have a cell in this row
				$availability = $interval->getAvailability() ? ' data-available' : "";
				$tableRows[$j % $intervalsCount] .= "<td{$availability}></td>" . PHP_EOL;
				$j++;
			}

			// increase the count to signify that
			// the first column has been generated
			// and only add cells when ($i > 0)
			$i++;
		}

		// wrap each row in the table with HTML
		$renderRow = function ($html, $row) {
			return $html . "<tr>{$row}</tr>" . PHP_EOL;
		};
		$tableRows = array_reduce($tableRows, $renderRow);
		// generate the HTML
		$thead = "<thead><tr>{$tableHeaders}</tr></thead>";
		$tbody = "<tbody>{$tableRows}</tbody>";
		$this->html = "<table>{$thead}{$tbody}</table>";
	}

	/**
	 * Render the given schedule
	 *
	 * Render the schedule passed, or if invoked without passing
	 * a schedule, try to use the schedule attached to the view.
	 *
	 * @param AbaLookup\Entity\Schedule $schedule The schedule to render.
	 * @throws InvalidArgumentException
	 */
	public function __invoke(ScheduleEntity $schedule = NULL)
	{
		if (isset($schedule)) {
			$this->render($schedule);
			return $this;
		}
		$view = $this->getView();
		if (isset($view->schedule) && $view->schedule instanceof ScheduleEntity) {
			$this->render($view->schedule);
			return $this;
		}
		throw new InvalidArgumentException();
	}

	/**
	 * Return the full HTML for the schedule
	 */
	public function html()
	{
		return $this->html;
	}

	/**
	 * Return the option elements for the times in the schedule
	 */
	public function getTimeOptions()
	{
		return $this->timeOptions;
	}

	/**
	 * Return the option elements for the days in the schedule
	 */
	public function getDayOptions()
	{
		return $this->dayOptions;
	}
}
