<?php

namespace MavenEngage\Admin\Controllers;

class Dashboard extends \MavenEngage\Admin\Controllers\EngageAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function admin_init() {
		
	}

	public function registerRoutes( $routes ) {

		$routes[ '/mavenengage/dashboard' ] = array(
		    array( array( $this, 'getStatistics' ), \WP_JSON_Server::READABLE )
		);

		return $routes;
	}

	public function getView( $view ) {

		switch ( $view ) {
			case "dashboard":
				return $this->getOutput()->getAdminView( "{$view}" );
		}

		return $view;
	}
	
	public function getStatistics( $filter = array() ) {

		$campaignScheduleManager = new \MavenEngage\Core\CampaignScheduleManager();

		$statFilter = new \MavenEngage\Core\Domain\CampaignStatisticsFilter();

		if ( key_exists( 'fromDate', $filter ) && $filter[ 'fromDate' ] ) {
			$statFilter->setFromDate( $filter[ 'fromDate' ] );
		}

		if ( key_exists( 'toDate', $filter ) && $filter[ 'toDate' ] ) {
			$statFilter->setToDate( $filter[ 'toDate' ] );
		}

		if ( key_exists( 'campaignId', $filter ) && $filter[ 'campaignId' ] ) {
			$statFilter->setCampaignId( $filter[ 'campaignId' ] );
		}

		$stats = $campaignScheduleManager->getStatistics( $statFilter );

		$this->getOutput()->sendApiResponse( $stats );
	}

}
