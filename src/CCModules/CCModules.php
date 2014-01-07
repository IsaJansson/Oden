<?php
/**
* A controller to manage and analyse all modules in Oden 
* @package OdenCore
*/

class CCModules extends CObject implements IController {

	// Constructor 
	public function __construct() {parent::__construct();}

	// Show a index-page wich displays what the user can do in this controller
	public function Index() {
		$modules = new CMModules();
		$controllers = $modules->AvaliableControllers();
		$allModules = $modules->ReadAndAnalyse();
		$this->views->SetTitle('Manage Modules')
					->AddInclude(__DIR__ . '/index.tpl.php', array('controllers'=>$controllers), 'primary')
					->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
	} 

	// Show a index-page wich displays what the user can do in this controller
	public function Install() {
	    $modules = new CMModules();
	    $results = $modules->Install();
	    $allModules = $modules->ReadAndAnalyse();
	    $this->views->SetTitle('Install Modules')
	                ->AddInclude(__DIR__ . '/install.tpl.php', array('modules'=>$results), 'primary')
	                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
	}


}