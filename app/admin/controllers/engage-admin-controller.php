<?php

namespace MavenEngage\Admin\Controllers;

abstract class EngageAdminController extends \Maven\Core\UI\AdminController{
	
	public function __construct(){
		
		parent::__construct( \MavenEngage\Settings\EngageRegistry::instance() );
		
		// We set the message manager and the key generator
		//$this->setMessageManager( \Maven\Core\Message\MessageManager::getInstance( new \Maven\Core\Message\UserMessageKeyGenerator() ) );
	}
	
}
