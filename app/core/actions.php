<?php

namespace MavenEngage\Core;

class Actions {


	/**
	 * @label Add Product
	 * @action AddEvent
	 * @description It will fire when an event is added
	 * @param \MavenShop\Core\Domain\Product $mavenProduct
	 */
	/*public static function AddProduct( \MavenShop\Core\Domain\Product $mavenProduct ) {

		$event = new \Maven\Tracking\Event();
		$event->setAction( 'Add Product' );
		$event->setCategory( 'Products' );
		$event->setLabel( 'Added Product' . $mavenProduct->getName() );
		$event->setValue( $mavenProduct->getId() );
		$event->addProperty( 'Name:', $mavenProduct->getName() );

		\Maven\Tracking\Tracker::addEvent( $event );

		do_action( 'action:mavenShop/event/add', $mavenProduct );
		
		
	}*/
}
