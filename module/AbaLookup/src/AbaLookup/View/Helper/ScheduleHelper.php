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
	 * @var Doctrine\Common\Collections\ArrayCollection
	 *
	 * The collection of days in a week
	 */
	protected $days;

	/**
	 * @var Schedule
	 *
	 * The schedule object to render
	 */
	protected $schedule;

	/**
	 * Magic method called when invoked
	 *
	 * @return $this
	 */
	public function __invoke(Schedule $schedule)
	{
		$this->schedule = $schedule;
		$this->days = $schedule->getDays();
		$this->intervals = $this->days->first()->getIntervals();
		return $this;
	}

	/**
	 * Return the days of the week as options
	 *
	 * Return a string of HTML in which each of the days of the week
	 * are represented by option tags (<option>).
	 *
	 * @return string
	 * @throws InvalidArgumnentException
	 */
	public function getSelectOptionsForDays($index = 0)
	{
		if (!isset($index) || !is_int($index)) {
			throw new InvalidArgumentException(sprintf(
				'Selected index must be an integer.'
			));
		}
		$markup = '';
		$i = 0;
		foreach ($this->days as $day) {
			$markup .= sprintf(
				'<option value="%d"%s>%s</option>',
				$day->getDay(),
				($i === $index) ? ' selected' : '',
				$day->getName()
			);
			++$i;
		}
		return $markup;
	}

	/**
	 * Return the daily intervals as options
	 *
	 * Return a string of HTML in which each possible daily interval
	 * is represented by an option tag (<option>).
	 *
	 * @param int $index The selected option index.
	 * @return string
	 * @throws InvalidArgumnentException
	 */
	public function getSelectOptionsForTimes($index = 0)
	{
		if (!isset($index) || !is_int($index)) {
			throw new InvalidArgumentException(sprintf(
				'Selected index must be an integer.'
			));
		}
		$markup = '';
		$i = 0;
		foreach ($this->intervals as $interval) {
			$markup .= sprintf(
				'<option value="%d"%s>%s</option>',
				$interval->getStartTime(),
				($i === $index) ? ' selected' : '',
				$this->formatMilitaryTime($interval->getStartTime())
			);
			++$i;
		}
		return $markup;
	}

	/**
	 * Returns the HTML representing the schedule.
	 *
	 * @param array $class The class names to apply to the schedule.
	 * @return string
	 */
	public function renderSchedule(array $class = array())
	{
		$numberOfDays = $this->days->count();
		$numberOfIntervals = $this->intervals->count() + 1;
		$rows = [];
		for ($i = 0; $i < $numberOfIntervals; ++$i) {
			$data = '';
			for ($j = 0; $j < $numberOfDays; ++$j) {
				$day = $this->days->get($j);
				$interval = $day->getIntervals()->get($i - 1);
				if ($j === 0) {
					if ($i === 0) {
						// top left corner
						$data .= '<td></td>';
					} else {
						// leftmost column
						$data .= sprintf(
							'<td>%s</td>',
							$this->formatMilitaryTime($interval->getStartTime())
						);
					}
				}
				if ($i === 0) {
					// headers row
					$data .= sprintf(
						'<th>%s</th>',
						$day->getAbbrev()
					);
				} else {
					// body rows
					$data .= sprintf(
						'<td%s></td>',
						$interval->isAvailable() ? ' data-available="true"' : ''
					);
				}
			}
			$rows[] = sprintf('<tr>%s</tr>', $data);
		}
		if (count($class) > 0) {
			$class = sprintf(' class="%s"', implode(' ', $class));
		} else {
			$class = '';
		}
		return sprintf(
			'<table%s><thead>%s</thead><tbody>%s</tbody></table>',
			$class,
			$rows[0],
			implode('', array_slice($rows, 1))
		);
	}

	/**
	 * Format and return the given military time value
	 *
	 * @param int $military The time in military format.
	 * @return string
	 * @throws InvalidArgumnentException
	 */
	protected function formatMilitaryTime($military)
	{
		if (!isset($military)) {
			throw new InvalidArgumentException();
		}
		$padded = str_pad($military, 4, '0', STR_PAD_LEFT);
		$dateTime = new DateTime($padded);
		return ltrim($dateTime->format('h:i A'), "0");
	}
}
