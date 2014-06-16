<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CronJobs {

	const SendCampaignHook = "maven-engage/campaigns/sendCampaign";
	
	public static function init() {
		
		$campaignManager = new CampaignManager();

		\Maven\Core\HookManager::instance()->addWp( array( '\MavenEngage\Core\CronJobs', 'setupAbandonedCartsSchedule' ) );

		\Maven\Core\HookManager::instance()->addAction( self::SendCampaignHook, array( $campaignManager, 'checkCampaignsExpiration' ) );

		\Maven\Core\HookManager::instance()->addFilter( 'cron_schedules', array( '\MavenEngage\Core\CronJobs', 'addScheduleInterval' ) );
		
	}

	public static function setupAbandonedCartsSchedule() {
		
		if ( ! wp_next_scheduled( self::SendCampaignHook ) ) {
			wp_schedule_event( time(), 'every5minutes', self::SendCampaignHook );
		}
	}

	function addScheduleInterval( $schedules ) {

		// add a '5 minutes' schedule to the existing set
		$schedules[ 'every5minutes' ] = array(
		    'interval' => 5 * 60,
		    'display' => __( 'Every 5 minutes' )
		);

		return $schedules;
	}
	
	public static function removeCronJobs() {

		wp_clear_scheduled_hook( self::SendCampaignHook );
	}

}
