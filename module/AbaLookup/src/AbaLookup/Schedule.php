<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AbaLookup;

use
	AbaLookup\Entity\Calendar,
	AbaLookup\Entity\CalendarIntervals as Interval,
;

class Schedule
{
	public function indexAction() {
		if (!$this->loggedIn()) return $this->redirect()->toRoute('user', array('action' => 'login'));

		$calendar = $this->getDefaultCalendar($this->currentUser());
		return $this->redirect()->toRoute('schedule', array(
			'action' => 'edit',
			'id' => $calendar->getId()
		));
	}

	public function editAction() {
		if (!$this->loggedIn()) return $this->redirect()->toRoute('user', array('action' => 'login'));
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$calendar = $this->getCalendarById($id);

		if ($calendar->getUser() != $this->currentUser())
			return $this->redirect()->toRoute('schedule', array('action' => 'index'));

		$intervals = $this->getCalendarIntervals($calendar);

		$mon = array();
		$tue = array();
		$wed = array();
		$thu = array();
		$fri = array();
		$sat = array();
		$sun = array();

		foreach ($intervals as $int) {
			$pushTo = $mon;
			if ($int->getWeekDay() == 2) $pushTo = $tue;
			if ($int->getWeekDay() == 3) $pushTo = $wed;
			if ($int->getWeekDay() == 4) $pushTo = $thu;
			if ($int->getWeekDay() == 5) $pushTo = $fri;
			if ($int->getWeekDay() == 6) $pushTo = $sat;
			if ($int->getWeekDay() == 7) $pushTo = $sun;
		}

		return new ViewModel(array(
			'id' => $id,
			'calendar' => $calendar,
			'intervals' => $intervals
		));
	}

	public function addintervalAction() {
		if (!$this->loggedIn()) return $this->redirect()->toRoute('user', array('action' => 'login'));
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$calendar = $this->getCalendarById($id);

		if ($calendar->getUser() != $this->currentUser())
			return $this->redirect()->toRoute('schedule', array('action' => 'index'));

		if (isset($_POST['start_time']) && isset($_POST['end_time']) && isset($_POST['day'])) {
			$start_time = $_POST['start_time'];
			$end_time = $_POST['end_time'];
			$day = $_POST['day'];

			$start_parts = explode(':', $start_time);
			$normalized_start = ($start_parts[0] * 60) + $start_parts[1];

			$end_parts = explode(':', $end_time);
			$normalized_end = ($end_parts[0] * 60) + $end_parts[1];

			$interval = new Interval($calendar, $normalized_start, $normalized_end, $day);
			$this->getEntityManager()->persist($interval);
			$this->getEntityManager()->flush();
		}

		return $this->redirect()->toRoute('schedule', array(
			'action' => 'edit',
			'id' => $calendar->getId()
		));
	}

	private function getDefaultCalendar($user) {
		$calendar = $this->getEntityManager()
						 ->getRepository('AbaLookup\Entity\Calendar')
						 ->findOneBy(array('user' => $user->getId()));
		if (!$calendar) {
			$calendar = new Calendar($user, true, "Default");
			$this->getEntityManager()->persist($calendar);
			$this->getEntityManager()->flush();
		}
		return $calendar;
	}

	private function getCalendarById($id) {
		return $this->getEntityManager()
					->getRepository('AbaLookup\Entity\Calendar')
					->findOneBy(array('id' => $id));
	}

	private function getCalendarIntervals($calendar) {
		return $this->getEntityManager()
					->getRepository('AbaLookup\Entity\CalendarIntervals')
					->findBy(
						array('calendar' => $calendar->getId()),
						array('week_day' => 'ASC', 'start_time' => 'ASC')
					);
	}
}
