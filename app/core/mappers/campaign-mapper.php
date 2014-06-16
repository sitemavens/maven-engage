<?php

namespace MavenEngage\Core\Mappers;

use MavenEngage\Core\EngageConfig;

class CampaignMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( \MavenEngage\Core\EngageConfig::campaignTableName );
	}

	public function getAll( $orderBy = "schedule", $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$where = '';
		$values = array();

		if ( ! $orderBy )
			$orderBy = 'id';

		$values[] = $start;
		$values[] = $limit;

		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where 1=1 
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		$campaigns = array();

		foreach ( $results as $row ) {
			$campaign = new \MavenEngage\Core\Domain\Campaign();
			$this->fillObject( $campaign, $row );

			$campaigns[] = $campaign;
		}

		return $campaigns;
	}

	public function getCount() {

		$query = "select count(*)
				from {$this->tableName} 
				where 1=1";

		$query = $this->prepare( $query );

		return $this->getVar( $query );
	}

	/**
	 * Return a Campaign object
	 * @param int $id
	 * @return \MavenEngage\Core\Domain\Campaign
	 */
	public function get( $id ) {

		if ( ! $id || ! is_numeric( $id ) ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}

		$campaign = new \MavenEngage\Core\Domain\Campaign();

		$row = $this->getRowById( $id );

		if ( ! $row ) {
			throw new \Maven\Exceptions\NotFoundException();
		}

		$this->fillObject( $campaign, $row );

		return $campaign;
	}

	public function delete( $campaignId ) {

		$relationTable = EngageConfig::campaignScheduleTableName;

		$query = "DELETE FROM {$relationTable} where campaign_id = %d";

		$query = $this->db->prepare( $query, $campaignId );

		$this->executeQuery( $query );

		parent::delete( $campaignId );

		return true;
	}

	/** Create or update the campaign to the database
	 * 
	 * @param \MavenEngage\Core\Domain\Campaign $campaign
	 * @return \MavenEngage\Core\Domain\Campaign
	 */
	public function save( \MavenEngage\Core\Domain\Campaign $campaign ) {

		$campaign->sanitize();

		$data = array(
		    'schedule_value' => $campaign->getScheduleValue(),
		    'schedule_unit' => $campaign->getScheduleUnit(),
		    'subject' => $campaign->getSubject(),
		    'body' => $campaign->getBody(),
		    'enabled' => $campaign->isEnabled() ? 1 : 0
		);

		$format = array(
		    '%d', //schedule_value
		    '%s', //schedule_unit
		    '%s', //subject
		    '%s', //body	   
		    '%d' //enabled
		);

		if ( ! $campaign->getId() ) {
			try {

				$campaignId = $this->insert( $data, $format );
			} catch ( \Exception $ex ) {

				return \Maven\Core\Message\MessageManager::createErrorMessage( $ex->getMessage() );
			}

			$campaign->setId( $campaignId );
		} else {
			$this->updateById( $campaign->getId(), $data, $format );
		}

		return $campaign;
	}

	public function fill( $object, $row ) {
		$this->fillObject( $object, $row );
	}

}
