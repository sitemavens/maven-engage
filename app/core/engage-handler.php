<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class EngageHandler {

	private static $entryPointVar = 'maven_engage_handler';

	const KeyVar = 'key';

	public function __construct() {
		;
	}

	public static function init() {

		$engageSettings = \MavenEngage\Settings\EngageRegistry::instance();

		add_rewrite_rule( "^{$engageSettings->getRecoverOrderUrl()}([^/]*)/?", "index.php?" . self::$entryPointVar . '=1&' . self::KeyVar . '=$matches[1]', 'top' );
	}

	public static function queryVars( $query_vars ) {

		$query_vars[] = self::$entryPointVar;
		$query_vars[] = self::KeyVar;

		return $query_vars;
	}

	public static function parseRequest( &$wp ) {

		if ( array_key_exists( self::$entryPointVar, $wp->query_vars ) ) {

			//$request = \Maven\Core\Request::current();
			$code = isset( $wp->query_vars[ self::KeyVar ] ) ? $wp->query_vars[ self::KeyVar ] : false;
			// Check if there is any object to print

			if ( ! $code ) {
				throw new \Maven\Exceptions\MavenException( 'Invalid credentials' );
			}

			$scheduleManager = new CampaignScheduleManager();

			$result = $scheduleManager->recoverOrder( $code );

			if ( $result ) {
				
				$mavenSettings = \Maven\Settings\MavenRegistry::instance();
				$redirect_url=site_url( $mavenSettings->getCartUrl() );
				//var_dump($url);
				
				wp_redirect( $redirect_url );
			} else
				die( 'Invalid data' );
		}

		return;
	}

}
