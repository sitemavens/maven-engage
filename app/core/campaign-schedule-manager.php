<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CampaignScheduleManager {

	private $mapper;

	public function __construct() {

		$this->mapper = new Mappers\CampaignScheduleMapper();
	}

	public static function init() {

		$campaignScheduleManager = new CampaignScheduleManager();

		\Maven\Core\HookManager::instance()->addAction( 'maven/cart/newOrder', array( $campaignScheduleManager, 'registerNewOrder' ) );

		\Maven\Core\HookManager::instance()->addAction( 'maven/order/completed', array( $campaignScheduleManager, 'registerCompletedOrder' ) );
	}

	public function recoverOrder( $code ) {

		if ( $code ) {
			$campaignSchedule = $this->mapper->getByCode( $code );

			if ( $campaignSchedule ) {

				//Recover order
				$cart = \Maven\Core\Cart::current();

				$order = $cart->loadOrder( $campaignSchedule->getOrderId() );

				if ( $order ) {
					//Add recovered state to order
					$status = \Maven\Core\OrderStatusManager::getRecoveredStatus();

					$order->setStatus( $status );

					$orderManager = new \Maven\Core\OrderManager();
					$orderManager->addOrder( $order, true );

					//update return date
					$today = \Maven\Core\MavenDateTime::getWPCurrentDateTime();
					$date = new \Maven\Core\MavenDateTime( $today );
					$campaignSchedule->setReturnDate( $date->mySqlFormatDateTime() );

					$this->addCampaignSchedule( $campaignSchedule );

					return true;
				}
			}
		}

		return false;
	}

	/**
	 * 
	 * @param \MavenEngage\Core\Domain\CampaignSchedule $campaignSchedule
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function addCampaignSchedule( \MavenEngage\Core\Domain\CampaignSchedule $campaignSchedule ) {

		$campaignScheduleToUpdate = new Domain\CampaignSchedule();

		if ( is_array( $campaignSchedule ) ) {
			\Maven\Core\FillerHelper::fillObject( $campaignScheduleToUpdate, $campaignSchedule );
		} else {
			$campaignScheduleToUpdate = $campaignSchedule;
		}

		return $this->mapper->save( $campaignScheduleToUpdate );
	}

	public function registerNewOrder( \Maven\Core\Domain\Order $order ) {
		\Maven\Loggers\Logger::log()->message( __METHOD__ );

		$engageSettings = \MavenEngage\Settings\EngageRegistry::instance();

		if ( $engageSettings->isEngageEnabled() ) {

			$campaignManager = new CampaignManager();

			//Get all campaigns
			$campaigns = $campaignManager->getAll();

			foreach ( $campaigns as $campaign ) {
				$schedule = new Domain\CampaignSchedule();

				$schedule->setCampaignId( $campaign->getId() );
				$schedule->setOrderId( $order->getId() );
				$nonce = \wp_create_nonce( "order-{$order->getId()}" );
				$schedule->setCode( $nonce );

				//Schedule every campaign
				$this->addCampaignSchedule( $schedule );
			}
		}
	}

	public function registerCompletedOrder( \Maven\Core\Domain\Order $order ) {

		$filter = new Domain\CampaignScheduleFilter();

		$filter->setOrderId( $order->getId() );

		//Get all schedules
		$schedules = $this->getAll( $filter );
		$today = \Maven\Core\MavenDateTime::getWPCurrentDateTime();
		$completedDate = new \Maven\Core\MavenDateTime( $today );
		foreach ( $schedules as $campaignSchedule ) {

			$campaignSchedule->setCompletedDate( $completedDate->mySqlFormatDateTime() );
			//Save every campaign
			$this->addCampaignSchedule( $campaignSchedule );
		}
	}

	/**
	 * 
	 * @param mixed $campaignScheduleId
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function get( $campaignScheduleId ) {

		if ( ! $campaignScheduleId ) {
			throw new \Maven\Exceptions\MissingParameterException( "Id is required" );
		}

		$campaignSchedule = $this->mapper->get( $campaignScheduleId );

		return $campaignSchedule;
	}

	/**
	 * 
	 * @param int $orderId
	 * @param int $campaignId
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 */
	public function getOrderSchedule( $orderId, $campaignId ) {

		$campaignSchedule = $this->mapper->getOrderSchedule( $orderId, $campaignId );

		return $campaignSchedule;
	}

	/**
	 * Get campaigns Schedule
	 * @return \MavenEngage\Core\Domain\CampaignSchedule[]
	 */
	public function getAll( Domain\CampaignScheduleFilter $filter, $orderBy = "id", $orderType = 'asc', $start = "0", $limit = "1000" ) {

		return $this->mapper->getAll( $filter, $orderBy, $orderType, $start, $limit );
	}

	public function getCount() {

		return $this->mapper->getCount();
	}

	/**
	 * 
	 * @return Domain\CampaignStatistic[]
	 */
	public function getStatistics( Domain\CampaignStatisticsFilter $filter ) {
		return $this->mapper->getStatistics( $filter );
	}

	/**
	 * 
	 * @return Domain\CampaignSchedule[]
	 */
	public function getPendingSchedules() {
		return $this->mapper->getPendingSchedules();
	}

	public function delete( $campaignScheduleId ) {

		$this->mapper->delete( $campaignScheduleId );

		return true;
	}

}
