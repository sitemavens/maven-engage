<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class IntelligenceReport {

	public static function generateData( $data, $lastRun ) {

		\Maven\Loggers\Logger::log()->message( 'MavenEngage/IntelligenceReport/generateData' );
		
		$table = new \Maven\Core\Domain\IntelligenceReport\Table();

		$table->setTitle( 'Engage Activity' );

		$table->addColumn( "# of Emails Sent" );
		$table->addColumn( "# of Recovered Carts" );
		$table->addColumn( "# of Completed Carts" );

		$campaignScheduleManager = new CampaignScheduleManager();
		$filter = new Domain\CampaignStatisticsFilter();
		//TODO: Should we send only data from $lastRun?
		//$filter->setFromDate($lastRun);

		$stats = $campaignScheduleManager->getStatistics( $filter );

		$countSent = $stats[ 0 ]->getSent();
		$countRecovered = $stats[ 0 ]->getRecovered();
		$countCompleted = $stats[ 0 ]->getCompleted();

		$table->addRow( array( $countSent, $countRecovered, $countCompleted ) );

		$data[] = $table;

		$gGraph = new \Maven\Core\Domain\IntelligenceReport\GoogleGraph();
		$gGraph->setTitle( 'Engage Chart' );
		$gGraph->setUrl( "http://chart.googleapis.com/chart?chs=300x225&cht=p&chco=00A2FF|80C65A|FF0000&chd=t:{$countSent},{$countRecovered},{$countCompleted}&chdl=Sent|Recovered|Completed" );


		$data[] = $gGraph;

		return $data;
	}

}
