<?php

namespace MavenEngage\Admin;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class AdminInitializer {

	private $classes = array();

	public function __construct() {

		\Maven\Core\HookManager::instance()->addAction( 'admin_enqueue_scripts', array( $this, 'registerScripts' ), 10, 1 );

		$registry = \MavenEngage\Settings\EngageRegistry::instance();
		$this->classes[ 'settings' ] = new Controllers\Settings();
		$this->classes[ 'dashboard' ] = new Controllers\Dashboard();


		foreach ( $this->classes as $class ) {
			\Maven\Core\HookManager::instance()->addFilter( "maven/views/get/{$registry->getPluginKey()}", array( $class, 'getView' ) );
		}
	}

	public function registerScripts( $hook ) {

		$registry = \MavenEngage\Settings\EngageRegistry::instance();

		if ( $hook == 'toplevel_page_mavenengage' ) {
			wp_enqueue_style( 'main.css', $registry->getStylesUrl() . "main.css", array(), $registry->getPluginVersion() );

			wp_enqueue_script( 'mavenEngageApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/directives/loading.js', $registry->getScriptsUrl() . "admin/directives/loading.js", 'mavenEngageApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/services/services.js', $registry->getScriptsUrl() . "admin/services/services.js", 'mavenEngageApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/main-nav.js', $registry->getScriptsUrl() . "admin/controllers/main-nav.js", 'mavenEngageApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/dashboard.js', $registry->getScriptsUrl() . "admin/controllers/dashboard.js", 'mavenEngageApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/settings.js', $registry->getScriptsUrl() . "admin/controllers/settings.js", 'mavenEngageApp', $registry->getPluginVersion() );
		}
	}

	public function registerRouters() {


		foreach ( $this->classes as $class ) {
			\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $class, 'registerRoutes' ) );
		}
	}

}
