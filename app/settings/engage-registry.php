<?php

namespace MavenEngage\Settings;

use \Maven\Settings\Option;

class EngageRegistry extends \Maven\Settings\WordpressRegistry {

	/**
	 * 
	 * @var StatsRegistry 
	 */
	private static $instance;

	protected function __construct() {

		parent::__construct();
	}

	/**
	 *
	 * @return \MavenEngage\Settings\EngageRegistry
	 */
	static function instance() {
		if ( ! isset( self::$instance ) ) {

			$adminEmail = get_bloginfo( 'admin_email' );


			$defaultOptions = array(
			    new Option(
				    "emailNotificationsTo", "Send email notifications to", $adminEmail, ''
			    ),
			    new Option(
				    "enabled", "Engage Enabled", true, '', \Maven\Settings\OptionType::CheckBox
			    ),
			    new Option(
				    "actions", "Actions", array(), ''
			    )
			);


			self::$instance = new self( );
			self::$instance->setOptions( $defaultOptions );
		}

		return self::$instance;
	}

	function getEmailNotificationsTo() {

		return $this->getValue( 'emailNotificationsTo' );
	}

	public function getActions() {

		return $this->getValue( 'actions' );
	}

	public function isActionEnabled( $actionName ) {
		$actions = $this->getActions();

		return ( isset( $actions[ $actionName ] ) );
	}

	public function isEngageEnabled() {
		return $this->getValue('enabled');
	}

	public function getRecoverOrderUrl() {
		return "maven/engage/continue-order/";
	}

}
