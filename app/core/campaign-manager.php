<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CampaignManager {

	private $mapper;

	public function __construct() {

		$this->mapper = new Mappers\CampaignMapper();
	}

	/**
	 * 
	 * @param \MavenEngage\Core\Domain\Campaign $campaign
	 * @return \MavenEngage\Core\Domain\Campaign
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function addCampaign( \MavenEngage\Core\Domain\Campaign $campaign ) {

		$campaignToUpdate = new Domain\Campaign();

		if ( is_array( $campaign ) ) {
			\Maven\Core\FillerHelper::fillObject( $campaignToUpdate, $campaign );
		} else {
			$campaignToUpdate = $campaign;
		}

		return $this->mapper->save( $campaignToUpdate );
	}

	/**
	 * 
	 * @param mixed $campaignId
	 * @return \MavenEngage\Core\Domain\Campaign
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function get( $campaignId ) {

		if ( ! $campaignId ) {
			throw new \Maven\Exceptions\MissingParameterException( "Event id is required" );
		}

		$campaign = $this->mapper->get( $campaignId );

		return $campaign;
	}

	/**
	 * Get campaigns
	 * @return \MavenEngage\Core\Domain\Campaign[]
	 */
	public function getAll( $orderBy = "id", $orderType = 'asc', $start = "0", $limit = "1000" ) {

		return $this->mapper->getAll( $orderBy, $orderType, $start, $limit );
	}

	public function getCount() {

		return $this->mapper->getCount();
	}

	public function delete( $orderId ) {

		$this->mapper->delete( $orderId );

		return true;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @param \MavenEngage\Core\Domain\Campaign $campaign
	 * @param \MavenEngage\Core\Domain\CampaignSchedule $schedule
	 */
	public function sendCampaignEmail( \Maven\Core\Domain\Order $order, Domain\Campaign $campaign, Domain\CampaignSchedule $schedule = NULL ) {
		$engageRegistry = \MavenEngage\Settings\EngageRegistry::instance();

		if ( $engageRegistry->isEngageEnabled() ) {
			if ( $order->hasItems() ) {
				$campaignScheduleManager = new CampaignScheduleManager();
				if ( ! $schedule ) {
					$schedule = $campaignScheduleManager->getOrderSchedule( $order->getId(), $campaign->getId() );
				}

				$email = false;
				$profile = false;
				if ( $order->hasUserInformation() && $order->getUser()->hasProfile() ) {
					$profile = $order->getUser()->getProfile();
					$email = $profile->getEmail();
				}
				//If we dont have an email, we look in the order contact
				if ( ! $email && $order->hasContactInformation() ) {
					$profile = $order->getContact();
					$email = $profile->getEmail();
				}
				//Check Billing Contact
				if ( ! $email && $order->hasBillingInformation() ) {
					$profile = $order->getBillingContact();
					$email = $profile->getEmail();
				}
				if ( ! $email && $order->hasShippingInformation() ) {
					$profile = $order->getShippingContact();
					$email = $profile->getEmail();
				}

				if ( $email ) {
					//Now we send the email
					$mavenSettings = \Maven\Settings\MavenRegistry::instance();
					$link = site_url( $engageRegistry->getRecoverOrderUrl() . $schedule->getCode() );

					$items = "";
					foreach ( $order->getItems() as $item ) {
						$items = $items . "<li>{$item->getName()}</li>";
					}
					$items = "<ul>{$items}</ul>";

					$variables = array(
					    'first_name' => $profile->getFirstName(),
					    'last_name' => $profile->getLastName(),
					    'items' => $items,
					    'organization_name' => $mavenSettings->getOrganizationName(),
					    'organization_signature' => $mavenSettings->getSignature(),
					    'link' => $link
					);

					$templateProcesor = new \Maven\Core\TemplateProcessor( $campaign->getBody(), $variables );

					$message = $templateProcesor->getProcessedTemplate();

					$mail = \Maven\Mail\MailFactory::build();

					$mail->to( $email )
						->message( $message )
						->subject( $campaign->getSubject() )
						->fromAccount( $mavenSettings->getSenderEmail() )
						->fromMessage( $mavenSettings->getSenderName() )
						->send();

					//set the send_date
					$today = \Maven\Core\MavenDateTime::getWPCurrentDateTime();
					$date = new \Maven\Core\MavenDateTime( $today );
					$schedule->setSendDate( $date->mySqlFormatDateTime() );
					$campaignScheduleManager->addCampaignSchedule( $schedule );
				} else {
					//We dont have a valid email address, delete the schedule
					$campaignScheduleManager->delete( $schedule->getId() );
				}
			} else {
				//the order dont have any items, delete the schedule
				$campaignScheduleManager->delete( $schedule->getId() );
			}
		}
	}

	//This function will iterate over the pending schedules, and send emails if the time has passed
	public function checkCampaignsExpiration() {

		\Maven\Loggers\Logger::log()->message( "Executing: " . __METHOD__ );

		$engageSettings = \MavenEngage\Settings\EngageRegistry::instance();

		if ( $engageSettings->isEngageEnabled() ) {

			$orderManager = new \Maven\Core\OrderManager();
			//get Pending schedules
			$campaignScheduleManager = new CampaignScheduleManager();

			$schedules = $campaignScheduleManager->getPendingSchedules();

			foreach ( $schedules as $schedule ) {
				//get campaign
				$campaign = $this->get( $schedule->getCampaignId() );

				if ( $campaign->isEnabled() ) {
					//check order

					if ( $orderManager->orderExists( $schedule->getOrderId() ) ) {
						//get Order
						$order = $orderManager->get( $schedule->getOrderId() );


						//Check if we have some email address to use
						if ( ! $order->hasBillingInformation() &&
							! $order->hasShippingInformation() &&
							! $order->hasContactInformation() &&
							! $order->hasUserInformation() ) {

							//TODO: We dont have any contact information, delete the schedule
							//$campaignScheduleManager->delete( $schedule->getId() );
							//We cant delete de schedule here. Maybe the order has just been created.
						} else {

							//Get order last update
							$orderLastUpdate = new \Maven\Core\MavenDateTime( $orderManager->getOrderLastUpdate( $order->getId() ) );

							$interval = $campaign->getScheduleString();
							// Campaign Limit
							$today = \Maven\Core\MavenDateTime::getWPCurrentDateTime();

							$limit = new \Maven\Core\MavenDateTime( $today );
							$limit->subFromIntervalString( $interval );
							\Maven\Loggers\Logger::log()->message( "Comparing order-{$order->getId()}:{$orderLastUpdate} and {$limit} " );
							if ( $orderLastUpdate < $limit ) {
								//Schedule time has passed, send email
								$this->sendCampaignEmail( $order, $campaign, $schedule );
							}
						}
					} else {
						//The order have been deleted, we should delete the schedule
						$campaignScheduleManager->delete( $schedule->getId() );
					}
				} else {
					//TODO: If disabled, maybe we should delete the schedule
					$campaignScheduleManager->delete( $schedule->getId() );
				}
			}
		}
	}

}
