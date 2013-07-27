<?php

namespace AbaLookup\View\Helper;

use
	AbaLookup\Entity\Schedule,
	DateTime,
	InvalidArgumentException,
	Zend\View\Helper\AbstractHelper
;

class ScheduleHelper extends AbstractHelper
{
	/**
	 * The HTML select options for the days of the week and times of the days
	 */
	protected $selectOptionDays;
	protected $selectOptionTimes;

	/**
	 * The HTML representing the schedule
	 */
	protected $markup = NULL;

	/**
	 * Generate the HTML for the schedule and "cache" it
	 *
	 * Renders the schedule.
	 *
	 * @param Schedule $schedule The schedule to render.
	 */
	protected function render(Schedule $schedule)
	{
		// if the schedule has already been rendered
		if (isset($this->markup)) {
			return $this->markup;
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
			$this->selectOptionDays .= "<option value=\"{$value}\">{$name}</option>";
			// the header for the day
			$tableHeaders .= "<th>{$abbrev}</th>";
			// all the intervals
			$intervals = $day->getIntervals()->toArray();
			$intervalsCount = count($intervals);
			$j = 0;

			foreach ($intervals as $interval) {
				$military = $interval->getStartTime();
				// only generate time options once
				// also only populate the leftmost column once
				if ($i == 0) {
					// pad the military time with zeros on the left and format for humans
					$time = ltrim((new DateTime(str_pad($military, 4, '0', STR_PAD_LEFT)))->format('h:i A'), "0");
					$this->selectOptionTimes .= "<option value=\"{$military}\">{$time}</option>";
					$tableRows[$j] = "<td>{$time}</td>";
				}
				// add a cell to the appropriate row in the table, and wrap
				// around once all the days (columns) have a cell in this row
				if ($interval->isAvailable()) {
					$tableRows[$j % $intervalsCount] .= '<td data-available></td>';
				} else {
					$tableRows[$j % $intervalsCount] .= '<td></td>';
				}
				$j++;
			}

			// increase the count to signify that
			// the first column has been generated
			// and only add cells when ($i > 0)
			$i++;
		}

		// wrap each row in the table with HTML
		$renderRow = function ($html, $row) {
			return $html . "<tr>{$row}</tr>";
		};
		$tableRows = array_reduce($tableRows, $renderRow);
		// generate the HTML
		$thead = "<thead><tr>{$tableHeaders}</tr></thead>";
		$tbody = "<tbody>{$tableRows}</tbody>";
		$this->markup = "<table>{$thead}{$tbody}</table>";
	}

	/**
	 * Called when invoking the view helper
	 *
	 * Passes the view attached to schedule to the {@code render}
	 * method.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __invoke()
	{
		$view = $this->getView();
		if (isset($view->schedule) && $view->schedule instanceof Schedule) {
			$this->render($view->schedule);
		} else {
			throw new InvalidArgumentException(sprintf(
				'No schedule attached to the view.'
			));
		}
		return $this;
	}

	/**
	 * Return the HTML representing the schedule
	 *
	 * @return string
	 */
	public function markup()
	{
		return $this->markup;
	}

	/**
	 * Return the select options for the times of the days
	 *
	 * @return string
	 */
	public function getSelectOptionTimes()
	{
		return $this->selectOptionTimes;
	}

	/**
	 * Return the select options for the days of the week
	 *
	 * @return string
	 */
	public function getSelectOptionDays()
	{
		return $this->selectOptionDays;
	}
}
