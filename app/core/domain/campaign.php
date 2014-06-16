<?php

namespace MavenEngage\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Campaign extends \Maven\Core\DomainObject {

	private $scheduleValue;
	private $scheduleUnit;
	private $subject;
	private $body;
	private $enabled;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'scheduleValue' => \Maven\Core\SanitizationRule::Integer,
		    'scheduleUnit' => \Maven\Core\SanitizationRule::Text,
		    'subject' => \Maven\Core\SanitizationRule::Text,
		    'body' => \Maven\Core\SanitizationRule::TextWithHtml,
		    'enabled' => \Maven\Core\SanitizationRule::Boolean
		);

		$this->setSanitizationRules( $rules );
	}

	public function getScheduleValue() {
		return $this->scheduleValue;
	}

	public function getScheduleUnit() {
		return $this->scheduleUnit;
	}

	public function setScheduleValue( $scheduleValue ) {
		$this->scheduleValue = $scheduleValue;
	}

	public function setScheduleUnit( $scheduleUnit ) {
		$this->scheduleUnit = $scheduleUnit;
	}

	public function getScheduleString() {
		return "{$this->getScheduleValue()} {$this->getScheduleUnit()}";
	}

	public function getBody() {
		return $this->body;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function setSubject( $subject ) {
		$this->subject = $subject;
	}

	public function setBody( $body ) {
		$this->body = $body;
	}

	public function setEnabled( $enabled ) {
		if ( $enabled === 'false' || $enabled === false ) {
			$this->enabled = FALSE;
		} else {
			$this->enabled = $enabled;
		}
	}

	public function isEnabled() {
		return $this->enabled;
	}

}
