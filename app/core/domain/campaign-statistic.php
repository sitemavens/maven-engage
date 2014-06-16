<?php

namespace MavenEngage\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CampaignStatistic extends \Maven\Core\DomainObject {

	private $sent;
	private $recovered;
	private $completed;
	
	private $recoveredPercent;
	private $completedPercent;
	
	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    
		);

		$this->setSanitizationRules( $rules );
	}
		
	public function getSent() {
		return $this->sent;
	}

	public function getRecovered() {
		return $this->recovered;
	}

	public function getCompleted() {
		return $this->completed;
	}

	public function getRecoveredPercent() {
		return $this->recoveredPercent;
	}

	public function getCompletedPercent() {
		return $this->completedPercent;
	}

	public function setSent( $sent ) {
		$this->sent = $sent;
	}

	public function setRecovered( $recovered ) {
		$this->recovered = $recovered;
	}

	public function setCompleted( $completed ) {
		$this->completed = $completed;
	}

	public function setRecoveredPercent( $recoveredPercent ) {
		$this->recoveredPercent = $recoveredPercent;
	}

	public function setCompletedPercent( $completedPercent ) {
		$this->completedPercent = $completedPercent;
	}



}
