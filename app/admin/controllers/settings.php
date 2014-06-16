<?php

namespace MavenEngage\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Settings extends EngageAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function registerRoutes( $routes ) {

		$routes[ '/mavenengage/settings' ] = array(
		    array( array( $this, 'getSettings' ), \WP_JSON_Server::READABLE )
		);

		$routes[ '/mavenengage/settings/(?P<id>\d+)' ] = array(
		    array( array( $this, 'getSettings' ), \WP_JSON_Server::READABLE ),
		    array( array( $this, 'edit' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);


		return $routes;
	}

	public function edit( $id, $data ) {


		$settings = \MavenEngage\Settings\EngageRegistry::instance()->getOptions();

		foreach ( $settings as $setting ) {
			if ( isset( $data[ $setting->getName() ] ) ) {
				$setting->setValue( $data[ $setting->getName() ] );
			}
		}

		\MavenEngage\Settings\EngageRegistry::instance()->saveOptions( $settings );

		//convert options to setting
		$entity = array();
		foreach ( $settings as $option ) {
			$entity[ $option->getName() ] = $option->getValue();
		}

		$this->getOutput()->sendApiResponse( $entity );
	}

	public function getView( $view ) {

		switch ( $view ) {
			case "settings":
				$this->addJSONData( "settingsCached", array( "test" => 1234, "chau" => false ) );
				return $this->getOutput()->getAdminView( "settings/{$view}" );
		}

		return $view;
	}

	public function getSettings() {

		$registry = \MavenEngage\Settings\EngageRegistry::instance();

		$options = $registry->getOptions();
		$entity = array();
		foreach ( $options as $option ) {
			$entity[ $option->getName() ] = $option->getValue();
		}

		$this->getOutput()->sendApiResponse( $entity );
	}

}
