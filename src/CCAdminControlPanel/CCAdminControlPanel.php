<?php
/**
* Admin control panel to manage admin stuff 
* @package OdenCore
*/
class CCAdminControlPanel extends CObject implements IController {
	
	// Constructor 
	public function __construct() {
		parent::__construct();
	}

	// Show profile information
	public function Index() {
		$this->views->SetTitle('ACP: Admin Control Panel');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php');
	}
	
}