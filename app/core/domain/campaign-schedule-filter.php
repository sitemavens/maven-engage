<?php

namespace MavenEngage\Core\Domain;

class CampaignScheduleFilter {

	private $orderId;
	
	private function protectField( $field ){
		
		if ( ! ( $field instanceof \Maven\Core\MavenDateTime ) )
			return esc_sql( sanitize_text_field( $field )) ;
		
		return $field;
	}

	
	public function __construct() {
		;
	}

	public function getOrderId() {
		return $this->orderId;
	}

	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

}

