<?php

namespace MavenEngage\Core\Domain;

class CampaignStatisticsFilter {

	private $campaignId;
	private $fromDate;
	private $toDate;
	
	private function protectField( $field ){
		
		if ( ! ( $field instanceof \Maven\Core\MavenDateTime ) )
			return esc_sql( sanitize_text_field( $field )) ;
		
		return $field;
	}

	
	public function __construct() {
		;
	}

	public function getCampaignId() {
		return $this->campaignId;
	}

	public function getFromDate() {
		return $this->fromDate;
	}

	public function getToDate() {
		return $this->toDate;
	}

	public function setCampaignId( $campaignId ) {
		$this->campaignId = $campaignId;
	}

	public function setFromDate( $fromDate ) {
		$this->fromDate = $fromDate;
	}

	public function setToDate( $toDate ) {
		$this->toDate = $toDate;
	}



}



