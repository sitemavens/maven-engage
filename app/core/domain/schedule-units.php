<?php

namespace MavenEngage\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ScheduleUnits {

	const Minutes = "minutes";
	const Hours = "hourds";
	const Days = "days";
	const Weeks = "weeks";
	const Months = "month";
	const Years = "years";

	public static function getScheduleUnits() {

		return array(
		    self::Minutes => 'Minutes',
		    self::Hours => 'Hours',
		    self::Days => 'Days',
		    self::Weeks => 'Weeks',
		    self::Months => 'Months',
		    self::Years => 'Years'
		);
	}

}
