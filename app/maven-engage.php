<?php

/*
  Plugin Name: Maven Engage
  Plugin URI:
  Description: Maven Engage™
  Author: SiteMavens.com
  Version: 0.1
  Author URI:
 */

namespace MavenEngage;

use Maven\Core\Loader;

//If the validation was already loaded
if ( ! class_exists( 'MavenValidation' ) )
	require_once plugin_dir_path( __FILE__ ) . 'maven-validation.php';

// Check if Maven is activate, if not, just return.
if ( \MavenValidation::isMavenMissing() )
	return;

//Added actions class here, because there are issues with ReflectionClass on Settings controller
Loader::load( plugin_dir_path( __FILE__ ), array( 'settings/engage-registry', 'core/actions' ) );


// Instanciate the registry and set all the plugins attributes
$registry = Settings\EngageRegistry::instance();

$registry->setPluginDirectoryName( "maven-engage" );
$registry->setPluginDir( plugin_dir_path( __FILE__ ) );
$registry->setPluginUrl( defined( 'DEV_ENV' ) && DEV_ENV ? WP_PLUGIN_URL . "/maven-engage/" : plugin_dir_url( __FILE__ )  );
$registry->setPluginName( 'Maven Engage' );
$registry->setPluginShortName( 'meg' );
$registry->setPluginVersion( "0.1" );
$registry->setRequest( new \Maven\Core\Request() );
//$registry->setMail( new \Maven\Core\Mail() );

$registry->init();

/**
 * We need to register the namespace of the plugin. It will be used for autoload function to add the required files. 
 */
Loader::registerType( "MavenEngage", $registry->getPluginDir() );

Loader::load( $registry->getPluginDir(), 'core/installer.php' );

/**
 * 
 * Instantiate the installer
 *
 * * */
$installer = new \MavenEngage\Core\Installer();
register_activation_hook( __FILE__, array( &$installer, 'install' ) );
register_deactivation_hook( __FILE__, array( &$installer, 'uninstall' ) );

/**
 *  Create the Director and the plugin
 */
$director = \Maven\Core\Director::getInstance();

$director->createPluginElements( $registry );

// We need to initialize the custom post types
//Core\ShopConfig::init();
//Register actions and filters for external process in gateway
$hookManager = $director->getHookManager( $registry );

// Set the engage handler hooks
$hookManager->addInit( array( '\MavenEngage\Core\EngageHandler', 'init' ) );
$hookManager->addQueryVarsFilter( array( '\MavenEngage\Core\EngageHandler', 'queryVars' ) );
$hookManager->addParseRequest( array( '\MavenEngage\Core\EngageHandler', 'parseRequest' ) );

//Enable cron jobs
Core\CronJobs::init();


//Front\ShopFrontEnd::registerFrontEndHooks();
//$hookManager->addInit( array( 'MavenShop\Front\ShopFrontEnd','init' ) );

$hookManager->addInit( array( '\MavenEngage\Core\CampaignScheduleManager', 'init' ) );

$hookManager->addFilter( 'maven\core\intelligenceReport:data', array( 'MavenEngage\\Core\\IntelligenceReport', 'generateData' ), 10, 2 );


$adminInitizalizer = new Admin\AdminInitializer();
$hookManager->addAction( 'wp_json_server_before_serve', array( $adminInitizalizer, 'registerRouters' ) );


// Load admin scripts, if we are in the admin 
if ( is_admin() ) {

	// Initialize WP features
	$hookManager->addAdminInit( array( '\MavenEngage\Admin\Wp\Loader', 'adminInit' ) );


	/** Register the components * */
	$componentManager = $director->getComponentManager( $registry );

	/** Settings * */
	$settings = $componentManager->createComponent( 'Settings', 'MavenEngage\\Admin\\Controllers\\Settings' );

	$dashboard = $componentManager->createComponent( 'Dashboard', 'MavenEngage\\Admin\\Controllers\\Dashboard' );


	/** Register the menues * */
	$menuManager = $director->getMenuManager( $registry );

	$menuManager->registerMenu( $dashboard, 'Maven Engage', $registry->getAssetsUrl() . "images/icon.png" );
	$menuManager->registerMenu( $settings );
}


