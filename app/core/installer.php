<?php

namespace MavenEngage\Core;

class Installer {

	public function __construct() {
		;
	}

	public function install() {

		global $wpdb;

		$create = array(
		    "CREATE TABLE IF NOT EXISTS `mvneg_campaign` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `schedule_value` int(11) DEFAULT NULL,
			  `schedule_unit` varchar(50) DEFAULT NULL,
			  `subject` varchar(1024) DEFAULT NULL,
			  `body` text,
			  `enabled` tinyint(4) NOT NULL DEFAULT '0',
			  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,				
			  PRIMARY KEY (`id`)
			)",
		    "CREATE TABLE IF NOT EXISTS `mvneg_campaign_schedule` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) DEFAULT NULL,
			  `campaign_id` int(11) DEFAULT NULL,
			  `code` varchar(32) DEFAULT NULL,
			  `send_date` datetime DEFAULT NULL,
			  `return_date` datetime DEFAULT NULL,
			  `completed_date` datetime DEFAULT NULL,
			  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,					  
			  PRIMARY KEY (`id`)
			) ",
		    //DEFAULT CAMPAIGN
		    "INSERT INTO `mvneg_campaign` (
			`schedule_value` ,
			`schedule_unit` ,
			`subject` ,
			`body` ,
			`enabled` ,
			`last_update`
			)
			VALUES (
			'1',  'hours',  'You forgot something in your Cart',
			'<p>Dear [first_name] -</p>

<p>We noticed that you forgot the following items in your shopping cart on
<strong>[organization_name]</strong>:</p>
    <p>[items]</p>
<p>We know that sometimes life can get a bit hectic, so we wanted to remind
you it''s still there.</p>

<p>To complete everything, just click this link
<a href=''[link]''>[link]</a> and you''ll be back on the path.

<p>Thanks,<br> [organization_signature]</p>',  '1', 
			CURRENT_TIMESTAMP
			);"
		);

		foreach ( $create AS $sql ) {
			$wpdb->query( $sql );
		}
	}

	public function uninstall() {

		global $wpdb;

		$settings = \MavenEngage\Settings\EngageRegistry::instance();
		$settings->reset();
		//To danger to remove the tables in the uninstall process
		$drop = array(
//			"mvnse_campaign",
//			"mvnse_campaign_send"
		);


		foreach ( $drop AS $sql ) {
			if ( $wpdb->query( $sql ) === false )
				return false;
		}

		//Remove scheduled jobs
		CronJobs::removeCronJobs();
	}

}
