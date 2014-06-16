<?php

namespace MavenEngage\Core;

class EngageConfig {

	const objectTypeName = 'mvneg_object';
	const campaignTableName = 'mvneg_campaign';
	const campaignScheduleTableName = 'mvneg_campaign_schedule';
	
	public static function init() {

		add_action( 'init', array( __CLASS__, 'registerTypes' ) );

	}


	static function  registerTypes() {

		$showInMenu = WP_DEBUG;
		
	}

}


EngageConfig::init();
 
