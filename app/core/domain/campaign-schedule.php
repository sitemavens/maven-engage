<?php

namespace MavenEngage\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CampaignSchedule extends \Maven\Core\DomainObject {

	private $code;
	private $sendDate;
	private $returnDate;
	private $completedDate;
	
	/**
	 *
	 * @var \Maven\Core\Domain\Order 
	 */
	private $order;
	private $orderId;

	/**
	 *
	 * @var \MavenEngage\Core\Domain\Campaign
	 */
	private $campaign;
	private $campaignId;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'code' => \Maven\Core\SanitizationRule::Text,
		    'sendDate' => \Maven\Core\SanitizationRule::DateTime,
		    'returnDate' =>  \Maven\Core\SanitizationRule::DateTime,
		    'completedDate' => \Maven\Core\SanitizationRule::DateTime,
		    'orderId' => \Maven\Core\SanitizationRule::Integer,
		    'campaignId' => \Maven\Core\SanitizationRule::Integer,
		);

		$this->setSanitizationRules( $rules );
	}
	
	
	public function getSendDate() {
		return $this->sendDate;
	}

	public function getReturnDate() {
		return $this->returnDate;
	}

	public function getCompletedDate() {
		return $this->completedDate;
	}

	public function setSendDate( $sendDate ) {
		$this->sendDate = $sendDate;
	}

	public function setReturnDate( $returnDate ) {
		$this->returnDate = $returnDate;
	}

	public function setCompletedDate( $completedDate ) {
		$this->completedDate = $completedDate;
	}
	
	public function getCode() {
		return $this->code;
	}

	public function getOrderId() {
		return $this->orderId;
	}

	public function getOrder() {
		return $this->order;
	}

	public function getCampaignId() {
		return $this->campaignId;
	}

	public function getCampaign() {
		return $this->campaign;
	}

	public function setCode( $code ) {
		$this->code = $code;
	}

	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

	public function setOrder( \Maven\Core\Domain\Order $order ) {
		$this->order = $order;
	}

	public function setCampaignId( $campaignId ) {
		$this->campaignId = $campaignId;
	}

	public function setCampaign( \MavenEngage\Core\Domain\Campaign $campaign ) {
		$this->campaign = $campaign;
	}

}
