<?php
/**
* Standard controller layout.
* @package OdenCore
*/

class CCIndex extends CObject implements IController {

	// Construct 
	public function __construct() {
		parent::__construct();
	}

	// Implementing interface IController. All controllers must have a index action. 
	public function Index() {
		$modules = new CMModules();
        $controllers = $modules->AvaliableControllers();
        $this->views->SetTitle('Index')
                    ->AddInclude(__DIR__ . '/index.tpl.php', array(), 'primary')
                    ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('controllers'=>$controllers), 'sidebar');
	}


}