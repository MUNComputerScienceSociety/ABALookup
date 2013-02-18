<?php
//bootstrap.php
if (!class_exists("Doctrine\Common\Version", false)) {
	require_once "bootstrap_doctrine.php";
}

require_once "entities/Location.php";
require_once "entities/ParentTherapistExclusion.php";
require_once "entities/ParentTherapistInternals.php";
require_once "entities/User.php";
require_once "entities/UserCalendar.php";
require_once "entities/UserCalendarIntervals.php";
require_once "entities/UserLocation.php";